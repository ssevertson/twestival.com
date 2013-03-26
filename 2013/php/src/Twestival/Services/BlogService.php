<?php namespace Twestival\Services;

use Twestival\DAOs\BlogsDAO;

class BlogService extends BaseService
{
	function getBlogs()
	{
		return $this->container['dao.blogs']->items();
	}
	function getBySubdomain($subdomain)
	{
		$blog = $this->container['dao.blogs']->get($subdomain);
		return $blog;
	}
	function getUnassigned()
	{
		return $this->container['dao.blogs']->findUnassignedForActiveYear();
	}
	function create($subdomain)
	{
		return $this->container['dao.blogs']->create($subdomain);
	}
	
	private function addUrisToBlogs(&$blogs)
	{
		foreach($blogs as $blog)
		{
			$this->addUrisToBlog($blog);
		}
	}
	private function addUrisToBlog(&$blog)
	{
		$blog['BlogUri'] = 'http://'
				. $event['BlogSubdomain']
				. '.'
						. $this->container['request.domain']
						. $this->container['baseUri'];
	}
}
?>