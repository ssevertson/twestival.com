<?php namespace Twestival\Services;

class LoginService extends BaseService
{
	function authenticateSiteAdmin($username, $password)
	{
		$admin = $this->container['dao.site.admins'];
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
		$admin = $this->container['dao.event.admins'];
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
	
	private function generatePassword()
	{
		$generator = new \PWGen();
		return $generator->generate();
	}
	
	function createEventAdmin($eventID, $username, &$password = '')
	{
		if(!$password)
		{
			$password = $this->generatePassword();
		}
		
		$salt = substr(md5(rand()), 0, 16);
		
		return $this->container['dao.event.admins']->create($eventID, $username, $password, $salt);
	}
}
?>