<?php namespace Twestival\Resources\Helpers;

class Security extends BaseHelper
{
	public function _siteAdmin($text, $context)
	{
		return $this->container['security.siteAdmin'] ? $context->render($text) : '';
	}
	public function _currentBlogEventAdmin($text, $context)
	{
		return $this->container['security.blog.eventAdmin'] ? $context->render($text) : '';
	}
}

?>