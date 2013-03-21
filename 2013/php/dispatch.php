<?php

if (!@include('vendor/autoload.php'))
{
	die('Could not find autoloader');
}

$baseDir = realpath(dirname(__FILE__));
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

$request = new Tonic\Request(array(
	'uri' => getTonicURI($baseUri)
));

$container = new Twestival\Container($baseDir, $baseUri, $request->method == 'GET');

try
{
	$resource = $app->getResource($request);
	$resource->container = $container;
	$response = $resource->exec();
	
	if($container['connection.transaction.open'])
	{
		$connection = $container['connection'];
		$connection->commit();
	}
}
catch (Tonic\NotFoundException $e)
{
	// TODO: Log exception and redirect to generic error page with 404 response
	$response = new Tonic\Response(404, $e->getMessage());
}
catch (Tonic\UnauthorizedException $e)
{
	// TODO: Redirect to namespace-relative login page, instead of basic auth
	$response = new Tonic\Response(401, $e->getMessage());
	$response->wwwAuthenticate = 'Basic realm="My Realm"';
}
catch (Tonic\Exception $e)
{
	// Log exception and redirect to generic error page with $e->getCode() response
	$response = new Tonic\Response($e->getCode(), $e->getMessage());
}
catch (Exception $e)
{
	// Log exception and redirect to generic error page
}

$response->output();


function getTonicURI($baseUri)
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
	}
	
	if($baseUri && substr($uri, 0, strlen($baseUri)) == $baseUri)
	{
		$uri = substr($uri, strlen($baseUri));
	}
	
	return '/' . $namespace . $uri;
}

