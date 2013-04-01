<?php namespace Twestival\Services;

use Twestival\DAOs\BlogsDAO;

class BlogService extends BaseService
{
	function getBySubdomain($subdomain)
	{
		$blog = $this->container['dao.blogs']->getBySubdomain($subdomain);
		$this->addUrisToBlog($blog);
		$this->addDetailsToBlog($blog);
		return $blog;
	}
	function getByID($blogID)
	{
		$blog = $this->container['dao.blogs']->getByID($blogID);
		$this->addUrisToBlog($blog);
		$this->addDetailsToBlog($blog);
		return $blog;
	}
	function getUnassigned()
	{
		$blogs = $this->container['dao.blogs']->findUnassignedForActiveYear();
		$this->addUrisToBlogs($blogs);
		return $blogs;
	}
	
	function create($subdomain)
	{
		return $this->container['dao.blogs']->create($subdomain);
	}
	
	private function addUrisToBlogs(&$blogs)
	{
		foreach($blogs as &$blog)
		{
			$this->addUrisToBlog($blog);
		}
	}
	private function addUrisToBlog(&$blog)
	{
		$blog['BlogUri'] = 'http://'
				. $blog['Subdomain']
				. '.'
				. $this->container['request.domain']
				. $this->container['baseUri'];
	}
	
	private function addDetailsToBlog(&$blog)
	{
		$eventID = $blog['EventID'];
		$event = $this->container['service.event']->getEvent($eventID);
		$event['Charities'] = $this->container['service.event.charity']->getCharities($eventID);
		$event['TeamMembers'] = $this->container['service.event.teamMember']->getTeamMembers($eventID);
		$event['Sponsors'] = $this->container['service.event.sponsor']->getSponsors($eventID);
		$blog['Event'] = $event;
	}
}
?>