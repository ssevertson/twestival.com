<?php namespace Twestival\Resources;

class BaseResource extends \Tonic\Resource
{
	function renderMustacheHeaderFooter($template, $data = array())
	{
		$common = $this->container['service.common'];
		$summaryStats = $common->getSummaryStats();
		
		$merged = array_merge($data, $summaryStats);
		
		$merged['CurrentYear'] = $common->getMostRecentActiveYear();
		
		return $this->renderMustache($template, $merged);
	}
	
	function renderMustache($template, $data = array())
	{
		$data['BaseUri'] = $this->container['baseUri'];
		return $this->container['mustache.engine']->loadTemplate($template)->render($data);
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
	
	function hashToList($grouped)
	{
		$array = array();
		foreach($grouped as $key => $value)
		{
			array_push($array, array(
			'Key' => $key,
			'Value' => $value
			));
		}
		return $array;
	}
}
?>