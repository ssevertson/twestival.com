<?php namespace Twestival\Services;

use Twestival\DAOs\BlogsDAO;

class BlogPostService extends BaseService
{
	function getPosts($subdomain, $count, $offset)
	{
		return $this->container['dao.blog.posts']->items($subdomain, $count, $offset);
	}
	function getPost($subdomain, $blogPostID)
	{
		return $this->container['dao.blog.posts']->get($subdomain, $blogPostID);
	}
	function getPostCount($subdomain)
	{
		return $this->container['dao.blog.posts']->count($subdomain);
	}
}
?>