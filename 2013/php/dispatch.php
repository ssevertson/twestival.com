<?php

if (!@include('vendor/autoload.php'))
{
	die('Could not find autoloader');
}

$baseDir = realpath(dirname(__FILE__));
$baseUri = '/2013';
$namespace = getTonicNamespace();

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
	'uri' => getRequestTonicUri($namespace, $baseUri)
));

$container = new Twestival\Container($baseDir, $baseUri, $request->method == 'GET');

register_shutdown_function(function() use ($container) { handleShutdown($container); });
set_error_handler(function($type, $message, $file, $line) use ($container) { handleError($container, $type, $message, $file, $line); });

try
{
	$resource = $app->getResource($request);
	$resource->container = $container;
	$response = $resource->exec();
	
	if($container->offsetExists('connection.transaction.open'))
	{
		$connection = $container['connection'];
		$connection->commit();
	}
}
catch (Tonic\NotFoundException $e)
{
	$container['logger']->addError($e->getMessage());
	$response = buildRedirectResponse($request, $baseUri . '/error?code=404');
}
catch (Tonic\UnauthorizedException $e)
{
	$container['logger']->addError($e->getMessage());
	$response = buildRedirectResponse($request, $baseUri . '/login');
}
catch (Tonic\Exception $e)
{
	$container['logger']->addError($e->getMessage());
	$response = buildRedirectResponse($request, $baseUri . '/error?code=' . $e->getCode());
}
catch (RedirectException $e)
{
	$container['logger']->addInfo('Redirecting to ' . $e->getUri());
	$response = buildRedirectResponse($request, $baseUri . $e->getUri());
}
catch (Exception $e)
{
	$container['logger']->addError($e->getMessage());
	$response = buildRedirectResponse($request, $baseUri . '/error?code=500');
}

$response->output();

function getTonicNamespace()
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
	return $namespace;
}

function getRequestTonicUri($namespace, $baseUri)
{
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
	
	if($uri == '/error')
	{
		# Generic error handler without namespace
		return $uri;
	}
	else
	{
		return '/' . $namespace . $uri;
	}
}

function buildRedirectResponse($request, $uri)
{
	$response = new \Tonic\Response($request);
	$response->code = \Tonic\Response::MOVEDPERMANENTLY;
	$response->Location = $uri;
	return $response;
}

function handleShutdown($container) {
	$error = error_get_last();
	if($error !== NULL)
	{
		if($error['type'] == E_ERROR || $error['type'] == E_PARSE)
		{
			handleError($container, $error['type'], $error['message'], $error['file'], $error['line']);
		}
	}
}

function handleError($container, $type, $message, $file, $line)
{
	$container['logger']->addError("Error $type: $message ($file:$line)");
}