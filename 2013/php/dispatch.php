<?php

if (!@include('vendor/autoload.php'))
{
	die('Could not find autoloader');
}


$app = new Tonic\Application(array(
	'load' => array(
		'src/Twestival/Resources/*.php'
	),
	'mount' => array(
		'global' => '/global',
		'blog' => '/blog')
));

$baseDir = realpath(dirname(__FILE__));
$baseUri = '/2013';
$hostname = $_SERVER['HTTP_HOST'];
$domain = getRequestDomain($hostname);
$subdomain = getRequestSubdomain($hostname);
$uri = getRequestUri();
$namespace = getTonicNamespace($subdomain);

$request = new Tonic\Request(array(
	'uri' => getTonicUri($uri, $namespace, $baseUri),
	'uriMethodOverride' => TRUE
));

$isGet = $request->method == 'GET';

$container = new Twestival\Container(array(
		'baseDir' => $baseDir,
		'baseUri' => $baseUri,
		'readOnly' => $isGet,
		'request.protocol' => 'http://',
		'request.hostname' => $hostname,
		'request.domain' => $domain,
		'request.subdomain' => $subdomain,
		'request.uri' => 'http://' . $hostname . $uri
));

register_shutdown_function(function() use (&$container) { handleShutdown($container); });
set_error_handler(function($type, $message, $file, $line) use (&$container) { handleError($container, $type, $message, $file, $line); });

try
{
	$resource = $app->getResource($request);
	$resource->container = $container;
	
	$response = $resource->exec();
	
	if($container->offsetExists('connection.transaction.open') && $container['connection.transaction.open'])
	{
		$container['connection']->commit();
	}
}
catch (Tonic\NotFoundException $e)
{
	$container['logger']->addError($e->getMessage());
	$response = buildRedirectResponse($request, $baseUri . '/error?code=404', $isGet);
}
catch (Tonic\UnauthorizedException $e)
{
	$container['logger']->addError($e->getMessage());
	if($isGet)
	{
		setcookie('URI_POST_LOGIN', $uri, 0, '/', $domain, false, true);
	}
	$response = buildRedirectResponse($request, $baseUri, '/login', $isGet);
}
catch (Tonic\Exception $e)
{
	$container['logger']->addError($e->getMessage() . ':' . $e->getTraceAsString());
	$response = buildRedirectResponse($request, $baseUri, '/error?code=' . $e->getCode(), $isGet);
}
catch (Twestival\RedirectException $e)
{
	if($container->offsetExists('connection.transaction.open') && $container['connection.transaction.open'])
	{
		$container['connection']->commit();
	}
	
	$container['logger']->addInfo('Redirecting to ' . $e->getUri());
	$response = buildRedirectResponse($request, $baseUri, $e->getUri(), $isGet);
}
catch (Exception $e)
{
	$container['logger']->addError($e->getMessage() . ':' . $e->getTraceAsString());
	$response = buildRedirectResponse($request, $baseUri, '/error?code=500', $isGet);
}

$response->output();

function getRequestSubdomain($hostname)
{
	$subdomain = NULL;
	if(isset($hostname) && substr_count($hostname, '.') >= 2)
	{
		$subdomain = substr($hostname, 0, strpos($hostname, '.'));
		if('www' == $subdomain || 'local' == $subdomain)
		{
			$subdomain = NULL;
		}
	}
	return $subdomain;
}
function getRequestDomain($hostname)
{
	$domain = $hostname;
	if(isset($domain) && substr_count($domain, '.') >= 2)
	{
		$domain = substr($hostname, strpos($hostname, '.') + 1);
	}
	return $domain;
}

function getTonicNamespace($subdomain)
{
	return isset($subdomain) ? 'blog' : 'global';
}

function getRequestUri()
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
	return $uri;
}

function getTonicUri($uri, $namespace, $baseUri)
{
	if($baseUri && substr($uri, 0, strlen($baseUri)) == $baseUri)
	{
		$uri = substr($uri, strlen($baseUri));
	}
	
	if(in_array($uri, array('/error', '/logout')))
	{
		# Generic error handler without namespace
		return $uri;
	}
	else
	{
		return '/' . $namespace . $uri;
	}
}

function buildRedirectResponse($request, $baseUri, $uri, $temporary = FALSE)
{
	if(!strpos($uri, '//') && $baseUri && substr($uri, 0, strlen($baseUri)) != $baseUri)
	{
		$uri = $baseUri . $uri;
	}
	
	$response = new \Tonic\Response($request);
	$response->code = $temporary ? \Tonic\Response::TEMPORARYREDIRECT : \Tonic\Response::MOVEDPERMANENTLY;
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

?>