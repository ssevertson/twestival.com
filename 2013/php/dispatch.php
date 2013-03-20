<?php

if (!@include('vendor/autoload.php')) {
	die('Could not find autoloader');
}


$baseUri = '/2013';
$app = new Tonic\Application(array(
	'load' => array(
		'src/Twestival/Resources/*.php'
	),
	'mount' => array(
		'global' => '/global',
		'blog' => '/blog'),
	'baseUri' => $baseUri
));
$app->baseDir = dirname(__FILE__);
$request = new Tonic\Request(array(
	'uri' => getURI($baseUri)
));

$container = new Pimple();
$container['db_ro'] = function() {
	$config = require 'config/database_ro.php';
	try {
		return new \PDO(
			$config['dsn'],
			$config['username'],
			$config['password'],
			array(
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_PERSISTENT => false,
				\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
			));
	} catch (Exception $e) {
		throw $e;
	}
};

try {
	$resource = $app->getResource($request);
	$resource->container = $container;
	$response = $resource->exec();

} catch (Tonic\NotFoundException $e) {
	$response = new Tonic\Response(404, $e->getMessage());

} catch (Tonic\UnauthorizedException $e) {
	$response = new Tonic\Response(401, $e->getMessage());
	$response->wwwAuthenticate = 'Basic realm="My Realm"';
} catch (Tonic\Exception $e) {
	$response = new Tonic\Response($e->getCode(), $e->getMessage());
}

$response->output();




function getURI($baseUri)
{
	$namespace = 'global';
	$hostname = $_SERVER['HTTP_HOST'];
	if(isset($hostname) && substr_count($hostname, '.') >= 2)
	{
		$subdomain = substr($hostname, 0, strpos($hostname, '.'));
		if('www' != $subdomain && 'local' != $subdomain)
		{
			$namespace = 'blog';
		}
	}
	
	$uri = '';
	if(isset($_SERVER['REDIRECT_URL']))
	{
		// use redirection URL from Apache environment
		$uri = $_SERVER['REDIRECT_URL'];
	}
	elseif(isset($_SERVER['REQUEST_URI']))
	{
		// use request URI from environment
		$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}
	elseif(isset($_SERVER['PHP_SELF']) && isset($_SERVER['SCRIPT_NAME']))
	{
		// use PHP_SELF from Apache environment
		$uri = substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME']));
	} else {
		// fail
		throw new \Exception('URI not provided');
	}
	
	if($baseUri && substr($uri, 0, strlen($baseUri)) == $baseUri)
	{
		$uri = substr($uri, strlen($baseUri));
	}
	
	return '/' . $namespace . $uri;
}