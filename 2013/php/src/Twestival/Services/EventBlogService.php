<?php namespace Twestival\Services;

use Twestival\DAOs\EventBlogsDAO;

class EventBlogService extends BaseService
{
	function getEventBlogs() {
		$blogs = new EventBlogsDAO($this->container);
		return $blogs->getEventBlogs();
	}

	function getEventBlog($anEventID = '') {
		$blogs = new EventBlogsDAO($this->container);
		return $blogs->getEventBlog($anEventID);
	}
}
?>