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
		$blog['Event'] = $this->container['dao.events']->get($blog['EventID']);
		return $blog;
	}
}
?>