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
	 */
	function showLogin() {
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
	 */
	function authenticate() {
		$loginService = new LoginService($this->container);
		if($loginService->authenticateSiteAdmin($_POST['username'], $_POST['password']))
		{
			throw new \Twestival\RedirectException('/');
		}
		else
		{
			throw new \Tonic\UnauthorizedException('Invalid Username or Password');
		}
	}
}
?>