<?php namespace Twestival;

class Container extends \Pimple
{
	function __construct($baseDir, $baseUri, $readOnly)
	{
		$this['baseDir'] = $baseDir;
		$this['configDir'] = $baseDir . '/config';
		$this['baseUri'] = $baseUri;
		$this['readOnly'] = $readOnly;
		
		$this['session.config'] = $this->share(function($c)
		{
			return require $c['configDir'] . '/session.php';
		});
		$this['session'] = $this->share(function($c)
		{
			$config = $c['session.config'];
			foreach ($config as $key => $value)
			{
				ini_set('session.' . $key, $value);
			}
			
			$domain = $_SERVER['HTTP_HOST'];
			if(isset($hostname) && substr_count($hostname, '.') >= 2)
			{
				$domain = substr($domain, strpos($hostname, '.'));
			}
			
			session_set_cookie_params(0, '/', $domain);
			session_start();
			return $_SESSION;
		});
		$this['session.exists'] = $this->share(function($c)
		{
			$config = $c['session.config'];
			$sessionName = $config['name'];
			if(!isset($sessionName))
			{
				$sessionName = ini_get('session.name');
			}
			if(!isset($sessionName))
			{
				$sessionName = 'PHPSESSID';
			}
			
			return isset($_COOKIE[$sessionName]);
		});
		
		$this['logger.config'] = $this->share(function($c)
		{
			return require $c['configDir'] . '/logging.php';
		});
		$this['logger'] = $this->share(function($c)
		{
			$config = $c['logger.config'];
			
			$logger = new \Monolog\Logger($c['channel']);
			$logger->pushHandler(new \Monolog\Handler\RotatingFileHandler(
					$config['path'] . '/' . $config['file'],
					0,
					\Monolog\Logger::getLevelName($config['level'])));
			return $logger;
		});
		
		$this['connection.config'] = $this->share(function($c)
		{
			return $c['connection.config'];
		});
		$this['connection'] = $this->share(function($c)
		{
			$readOnly = $c['readOnly'];
			$config = require $c['configDir'] . ($readOnly ? '/database_ro.php' : '/database_rw.php');
			try
			{
				$connection = new \PDO(
					$config['dsn'],
					$config['username'],
					$config['password'],
					array(
						\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
						\PDO::ATTR_PERSISTENT => false,
						\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
					));
				$c['connection.open'] = TRUE;
				if(!$readOnly)
				{
					$connection->beginTransaction();
					$c['connection.transaction.open'] = TRUE;
				}
				return $connection;
			}
			catch (Exception $e)
			{
				$c['logger'].addError('Exception occurred establishing database connection to ' . $config['dsn']);
				throw $e;
			}
		});
		
		$this['blog.subdomain'] = $this->share(function($c)
		{
			$hostname = $_SERVER['HTTP_HOST'];
			$blogSubdomain = NULL;
			if(isset($hostname) && substr_count($hostname, '.') >= 2)
			{
				$subdomain = substr($hostname, 0, strpos($hostname, '.'));
				if('www' != $subdomain && 'local' != $subdomain)
				{
					$blogSubdomain = $subdomain;
				}
			}
			return $blogSubdomain;
		});
	}
};
?>