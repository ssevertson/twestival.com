<?php namespace Twestival\Services;

use Twestival\DAOs\BlogsDAO;

class BlogService extends BaseService
{
	function getBlogs($year)
	{
		return $this->container['dao.blogs']->items($year);
	}
	function getById($blogID)
	{
		return $this->container['dao.blogs']->getByID($blogID);
	}
	function getBySubdomain($subdomain)
	{
		return $this->container['dao.blogs']->getBySubdomain($subdomain);
	}
}
?>