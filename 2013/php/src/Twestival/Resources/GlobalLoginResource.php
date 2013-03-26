<?php namespace Twestival\Resources;

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
		$loginService = $this->container['service.login'];
		if($loginService->authenticateSiteAdmin($_POST['Username'], $_POST['Password']))
		{
			if(isset($_COOKIE['URI_POST_LOGIN']))
			{
				setcookie('URI_POST_LOGIN', FALSE, 0, '/', $this->container['request.domain'], false, true);
				throw new \Twestival\RedirectException($_COOKIE['URI_POST_LOGIN']);
			}
			else
			{
				throw new \Twestival\RedirectException('/admin');
			}
		}
		else
		{
			throw new \Tonic\UnauthorizedException('Invalid Username or Password');
		}
	}
}
?>