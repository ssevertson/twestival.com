<?php namespace Twestival\Resources;

use Twestival\Services\LoginService;

/**
 * @namespace global
 * @uri /login
 */
class GlobalLoginResource extends BaseResource
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
			'LoginType' => 'Admin',
		));
	}
	
	/**
	 * @method POST
	 * @provides text/html
	 */
	function authenticate()
	{
		$loginService = new LoginService($this->container);
		if($loginService->authenticateSiteAdmin($_POST['Username'], $_POST['Password']))
		{
			throw new \Twestival\RedirectException('/admin');
		}
		else
		{
			throw new \Tonic\UnauthorizedException('Invalid Username or Password');
		}
	}
}
?>