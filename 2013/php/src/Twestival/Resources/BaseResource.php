<?php namespace Twestival\Resources;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class BaseResource extends \Tonic\Resource
{
	protected function getRelativeUri($path)
	{
		return $this->container['baseUri'] . $path;
	}
	
	function renderMustache($template, $data)
	{
		$mustache = new Mustache_Engine(array(
			'loader' => new Mustache_Loader_FilesystemLoader($this->container['baseDir'] . '/src/Twestival/Views'),
			'helpers' => array(
				'format_number' => new Helpers\NumberFormatters()
			)
		));
		
		return $mustache->loadTemplate($template)->render($data);
	}
	
	function isSiteAdmin()
	{
		if(!$this->container['session.exists'])
		{
			return false;
		}
		$session = $this->container['session'];
		return 'SITE_ADMIN' == $session['scope'];
	}
	function requireSiteAdmin()
	{
		if(!$this->isSiteAdmin())
		{
			throw new \Tonic\UnauthorizedException;
		}
	}
	
	function isBlogEventAdmin($blog)
	{
		if(!$this->container['session.exists'])
		{
			return false;
		}
		$session = $this->container['session'];
		return 'SITE_ADMIN' == $session['scope']
				|| ('EVENT_ADMIN' == $session['scope'] && $blog == $session['blog.subdomain']);
	}
	function isCurrentBlogEventAdmin()
	{
		$blog = $this->container['blog.subdomain'];
		return isset($blog) && $this->isBlogEventAdmin($blog);
	}
	function requireCurrentBlogEventAdmin()
	{
		if(!$this->isCurrentBlogEventAdmin())
		{
			throw new \Tonic\UnauthorizedException;
		}
	}
}
?>