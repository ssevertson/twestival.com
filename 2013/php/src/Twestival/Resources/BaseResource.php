<?php namespace Twestival\Resources;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Twestival\Services\CommonService;

class BaseResource extends \Tonic\Resource
{
	protected function getRelativeUri($path)
	{
		return $this->container['baseUri'] . $path;
	}
	
	function renderMustacheHeaderFooter($template, $data = array())
	{
		$common = new CommonService($this->container);
		$summaryStats = $common->getSummaryStats();
		$merged = array_merge($data, $summaryStats);
		$year = $common->getMostRecentActiveYear();
		$merged['CurrentYear'] = $year;
		return $this->renderMustache($template, $merged);
	}
	
	function renderMustache($template, $data = array())
	{
		$mustache = new Mustache_Engine(array(
			'loader' => new Mustache_Loader_FilesystemLoader($this->container['baseDir'] . '/src/Twestival/Views'),
			'partials_loader' => new Mustache_Loader_FilesystemLoader($this->container['baseDir'] . '/src/Twestival/Views/Partials'),
			'helpers' => array(
				'format' => new Helpers\Formatters($this->container),
				'security' => new Helpers\Security($this->container)
			)
		));
		
		return $mustache->loadTemplate($template)->render($data);
	}
	

	function isSiteAdmin()
	{
		return $this->container['security.siteAdmin'];
	}
	function requireSiteAdmin()
	{
		if(!$this->isSiteAdmin())
		{
			throw new \Tonic\UnauthorizedException;
		}
	}
	
	function isCurrentBlogEventAdmin()
	{
		return $this->container['security.blog.eventAdmin'];
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