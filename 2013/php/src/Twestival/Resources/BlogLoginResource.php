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
	 */
	function authenticate()
	{
		$loginService = $this->container['service.login'];
		if($loginService->authenticateEventAdmin($_POST['Username'], $_POST['Password'], $this->container['request.subdomain']))
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