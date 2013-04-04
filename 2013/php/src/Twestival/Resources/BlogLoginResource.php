<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /login
 */
class BlogLoginResource extends BaseResource
{
	/**
	 * @method GET
	 * @provides text/html
	 * @requireSecure
	 */
	function showLogin()
	{
		$this->container['service.login']->logout();
		return $this->renderMustacheHeaderFooter('Login', array(
			'LoginType' => 'Event',
		));
	}
	
	/**
	 * @method POST
	 * @provides text/html
	 * @requireSecure
	 */
	function authenticate()
	{
		$loginService = $this->container['service.login'];
		if($loginService->authenticateEventAdmin($_POST['Username'], $_POST['Password'], $this->container['request.subdomain']))
		{
			if(isset($_COOKIE['URI_POST_LOGIN']))
			{
				setcookie('URI_POST_LOGIN', FALSE, 0, '/', $this->container['request.domain'], false, $this->container['request.secure']);
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