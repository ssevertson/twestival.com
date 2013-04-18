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
			
			session_start();
			return new Session();
		});
		$this['session.cookie'] = $this->share(function($c)
		{
			$config = $c['session.config'];
			$cookieName = NULL;
			if(isset($config['name']))
			{
				$cookieName = $config['name'];
			}
			if(!isset($sessionName))
			{
				$cookieName = ini_get('session.name');
			}
			if(!isset($sessionName))
			{
				$cookieName = 'PHPSESSID';
			}
			return $cookieName;	
		});
		$this['session.exists'] = $this->share(function($c)
		{
			return isset($_COOKIE[$c['session.cookie']]);
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
					\Monolog\Logger::getLevelName($config['level'])
			));
			
			if(isset($config['email.address']))
			{
				$logger->pushHandler(new \Monolog\Handler\SwiftMailerHandler(
						$c['email.mailer'],
						$c['email.message']
								->setSubject(isset($config['email.subject']) ? $config['email.subject'] : 'PHP Error')
								->setTo($config['email.address'])
				));
			}
			
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
				if($session->offsetExists('blog.subdomain'))
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
		
		$this['geonames.config'] = $this->share(function($c)
		{
			$configFile = $c['configDir'] . '/geonames.php';
			return require $configFile;
		});
		$this['geonames.client'] = $this->share(function($c)
		{
			$config = $c['geonames.config'];
			return new \Guzzle\Http\Client('http://api.geonames.org', $config);
		});
		$this['twitter.twestival.config'] = $this->share(function($c)
		{
			$configFile = $c['configDir'] . '/twitter_twestival.php';
			return require $configFile;
		});
		$this['twitter.twestival.client'] = $this->share(function($c)
		{
			$config = $c['twitter.twestival.config'];
			$client = new \Guzzle\Http\Client('https://api.twitter.com/{version}', array(
					'version' => '1.1'
			));
			$client->addSubscriber(new \Guzzle\Plugin\Oauth\OauthPlugin($config));
			return $client;
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
		
		$this['email.config'] = $this->share(function($c)
		{
			$configFile = $c['configDir'] . '/email.php';
			return require $configFile;
		});
		$this['email.transport'] = $this->share(function($c)
		{
			$config = $c['email.config'];
			return \Swift_SmtpTransport::newInstance(
					$config['hostname'],
					$config['port']
			);
		});
		$this['email.mailer'] = $this->share(function($c)
		{
			$transport = $c['email.transport'];
			$mailer = \Swift_Mailer::newInstance($transport);
			$logger = new \Swift_Plugins_Loggers_EchoLogger();
			$mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));
			return $mailer;
		});
		$this['email.message'] = function($c)
		{
			$config = $c['email.config'];
			$message = \Swift_SignedMessage::newInstance();
			$message
					->setFrom($config['from']);
			$signer = new \Swift_Signers_DKIMSigner(
					$config['dkim.key.private'],
					$config['dkim.domain'],
					$config['dkim.selector']);
			$signer
					->setHeaderCanon('relaxed')
					->setBodyCanon('relaxed')
					->setHashAlgorithm('rsa-sha1');
			$message->attachSigner($signer);
			return $message;
		};
		
		$this['service.common'] = $this->share(function($c)
		{
			return new \Twestival\Services\CommonService($c);
		});
		$this['service.year'] = $this->share(function($c)
		{
			return new \Twestival\Services\YearService($c);
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
		$this['service.event'] = $this->share(function($c)
		{
			return new \Twestival\Services\EventService($c);
		});
		$this['service.registration'] = $this->share(function($c)
		{
			return new \Twestival\Services\RegistrationService($c);
		});
		$this['service.thankyou'] = $this->share(function($c)
		{
			return new \Twestival\Services\ThankYouService($c);
		});
		$this['service.blog'] = $this->share(function($c)
		{
			return new \Twestival\Services\BlogService($c);
		});
		$this['service.blog.post'] = $this->share(function($c)
		{
			return new \Twestival\Services\BlogPostService($c);
		});
		$this['service.event.charity'] = $this->share(function($c)
		{
			return new \Twestival\Services\EventCharityService($c);
		});
		$this['service.event.teamMember'] = $this->share(function($c)
		{
			return new \Twestival\Services\EventTeamMemberService($c);
		});
		$this['service.event.sponsor'] = $this->share(function($c)
		{
			return new \Twestival\Services\EventSponsorService($c);
		});
		$this['service.location'] = $this->share(function($c)
		{
			return new \Twestival\Services\LocationService($c);
		});		
		$this['service.email'] = $this->share(function($c)
		{
			return new \Twestival\Services\EmailService($c);
		});
		
		$this['dao.event.admins'] = $this->share(function($c)
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
		$this['dao.promotions'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\EventPromotionsDAO($c);
		});
		$this['dao.pages'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\PagesDAO($c);
		});
		$this['dao.years'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\YearsDAO($c);
		});
		$this['dao.registrations'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\RegistrationsDAO($c);
		});
		$this['dao.blogs'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\BlogsDAO($c);
		});
		$this['dao.blog.posts'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\BlogPostsDAO($c);
		});
		$this['dao.event.charities'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\EventCharitiesDAO($c);
		});
		$this['dao.event.teamMembers'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\EventTeamMembersDAO($c);
		});
		$this['dao.event.sponsors'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\EventSponsorsDAO($c);
		});
		$this['dao.locations'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\LocationsDAO($c);
		});
		$this['dao.event.locations'] = $this->share(function($c)
		{
			return new \Twestival\DAOs\EventLocationsDAO($c);
		});
		
		
		$this['helper.format'] = $this->share(function($c)
		{
			return new \Twestival\Resources\Helpers\Formatters($c);
		});
		$this['helper.security'] = $this->share(function($c)
		{
			return new \Twestival\Resources\Helpers\Security($c);
		});
	}
};
?>