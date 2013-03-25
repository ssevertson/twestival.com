<?php namespace Twestival\Services;

use Twestival\DAOs\BlogsDAO;

class BlogService extends BaseService
{
	function getBlogs() {
		$blogs = new BlogsDAO($this->container);
		return $blogs->getBlogs();
	}

	function getBlog($aBlogID = '') {
		$blogs = new BlogsDAO($this->container);
		return $blogs->getBlog($aBlogID);
	}
}
?>