<?php namespace Twestival;

class Container extends \Pimple
{
	function __construct($array)
	{
		parent::__construct($array);
		
		$this['configDir'] = $array['baseDir'] . '/config';
		$this['viewDir'] = $array['baseDir'] . '/src/Twestival/Views';
		
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
			return new Session();
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
			return require $c['configDir'] . '/logger.php';
		});
		$this['logger'] = $this->share(function($c)
		{
			$config = $c['logger.config'];
			
			$logger = new \Monolog\Logger($config['channel']);
			$logger->pushHandler(new \Monolog\Handler\RotatingFileHandler(
					$config['path'] . '/' . $config['file'],
					0,
					\Monolog\Logger::getLevelName($config['level'])));
			return $logger;
		});
		
		$this['connection.config'] = $this->share(function($c)
		{
			$configFile = $c['configDir'] . ($c['readOnly'] ? '/database_ro.php' : '/database_rw.php');
			return require $configFile;
		});
		$this['connection'] = $this->share(function($c)
		{
			$readOnly = $c['readOnly'];
			$config = $c['connection.config'];
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
					$c['connection.transaction.open'] = $connection->beginTransaction();
				}
				return $connection;
			}
			catch (Exception $e)
			{
				$c['logger'].addError('Exception occurred establishing database connection to ' . $config['dsn']);
				throw $e;
			}
		});
		
		$this['security.scope'] = $this->share(function($c)
		{
			$scope = NULL;
			if($c['session.exists'])
			{
				$session = $c['session'];
				if($session->offsetExists('scope'))
				{
					$scope = $session['scope'];
				}
			}
			return $scope;
		});
		$this['security.user.id'] = $this->share(function($c)
		{
			$userID = NULL;
			if($c['session.exists'])
			{
				$session = $c['session'];
				if($session->offsetExists('user.id'))
				{
					$userID = $session['user.id'];
				}
			}
			return $userID;
		});
		$this['security.blog.subdomain'] = $this->share(function($c)
		{
			$subdomain = NULL;
			if($c['session.exists'])
			{
				$session = $c['session'];
				if($session->offsetExists('subdomain'))
				{
					$subdomain = $session['blog.subdomain'];
				}
			}
			return $subdomain;
		});
		$this['security.blog.eventAdmin'] = $this->share(function($c)
		{
			$scope = $c['security.scope'];
			if('SITE_ADMIN' == $scope)
			{
				return true;
			}
			else if('EVENT_ADMIN' == $scope)
			{
				$securitySubdomain = $c['security.blog.subdomain'];
				if(!$securitySubdomain)
				{
					return false;
				}
				
				$requestSubdomain = $c['request.subdomain'];
				if(!$requestSubdomain)
				{
					return false;
				}
				
				return $securitySubdomain == $requestSubdomain;
			
			}
			return false;
		});
		
		$this['security.siteAdmin'] = $this->share(function($c)
		{
			$scope = $c['security.scope'];
			return 'SITE_ADMIN' == $scope;
		});
		
		
		
		$this['service.common'] = $this->share(function($c)
		{
			return new \Twestival\Services\CommonService($c);
		});
		$this['service.login'] = $this->share(function($c)
		{
			return new \Twestival\Services\LoginService($c);
		});
		$this['service.page'] = $this->share(function($c)
		{
			return new \Twestival\Services\PageService($c);
		});
		$this['service.promotion'] = $this->share(function($c)
		{
			return new \Twestival\Services\PromotionService($c);
		});
		

		$this['dao.events.admins'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\EventAdminsDAO($c);
		});
		$this['dao.site.admins'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\SiteAdminsDAO($c);
		});
		$this['dao.events'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\EventsDAO($c);
		});
		$this['dao.pages'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\PagesDAO($c);
		});
		$this['dao.years'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\YearsDAO($c);
		});
		
		$this['helper.format'] = $this->share(function($c)
		{
			return new \Twestival\Resources\Helpers\Formatters($c);
		});
		$this['helper.security'] = $this->share(function($c)
		{
			return new \Twestival\Resources\Helpers\Security($c);
		});
		
		$this['mustache.loader.view'] = $this->share(function($c)
		{
			return new \Mustache_Loader_FilesystemLoader($c['viewDir']);
		});
		$this['mustache.loader.partial'] = $this->share(function($c)
		{
			return new \Mustache_Loader_FilesystemLoader($c['viewDir'] . '/Partials');
		});
		$this['mustache.engine'] = $this->share(function($c)
		{
			return new \Mustache_Engine(array(
				'loader' => $c['mustache.loader.view'],
				'partials_loader' => $c['mustache.loader.partial'],
				'helpers' => array(
						'format' => $c['helper.format'],
						'security' => $c['helper.security']
				)
			));
		});
	}
};
?>