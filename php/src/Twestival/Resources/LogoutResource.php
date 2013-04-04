<?php namespace Twestival\Resources;

/**
 * @uri /logout
 */
class LogoutResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function logout()
	{
		$this->container['service.login']->logout();
		throw new \Twestival\RedirectException('/');
	}
}
?>