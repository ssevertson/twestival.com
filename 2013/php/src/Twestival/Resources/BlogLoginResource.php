<?php namespace Twestival\Resources;

use Twestival\Services\LoginService;

/**
 * @namespace blog
 * @uri /login
 */
class BlogLoginResource extends BaseResource
{
	/**
	 * @method GET
	 * @provides text/html
	 */
	function showLogin()
	{
		if($this->container['session.exists'])
		{
			session_start();
			session_destroy();
		}
		
		return $this->renderMustacheHeaderFooter('Login', array(
			'LoginType' => 'Event',
		));
	}
	
	/**
	 * @method POST
	 * @provides text/html
	 */
	function authenticate()
	{
		$loginService = new LoginService($this->container);
		if($loginService->authenticateEventAdmin($_POST['username'], $_POST['password'], $this->container['request.blog.subdomain']))
		{
			# TODO: Do something real here - redirect somewhere?
			return $this->renderMustache('Login', array(
				'BodyID' => 'login',
				'LoginType' => 'Success!!!',
			));
		}
		else
		{
			throw new \Tonic\UnauthorizedException('Invalid Username or Password');
		}
	}
}
?>