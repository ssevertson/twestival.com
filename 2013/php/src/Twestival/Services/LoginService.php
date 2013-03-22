<?php namespace Twestival\Services;

use Twestival\DAOs\SiteAdminsDAO;
use Twestival\DAOs\EventAdminsDAO;

class LoginService extends BaseService
{
	function authenticateSiteAdmin($username, $password)
	{
		$admin = new SiteAdminsDAO($this->container);
		$userID = $admin->authenticate($username, $password);
		
		if($userID)
		{
			$session = $this->container['session'];
			$session['scope'] = 'SITE_ADMIN';
			$session['user.id'] = $userID;
			session_write_close();
		}
		
		return $userID;
	}
	
	function authenticateEventAdmin($username, $password, $blogSubdomain)
	{
		$admin = new EventAdminsDAO($this->container);
		$userID = $admin->authenticate($username, $password, $blogSubdomain);
		
		if($userID)
		{
			$session = $this->container['session'];
			$session['scope'] = 'EVENT_ADMIN';
			$session['user.id'] = $userID;
			$session['blog.subdomain'] = $blogSubdomain;
			session_write_close();
		}
		
		return $userID;
	}
}
?>