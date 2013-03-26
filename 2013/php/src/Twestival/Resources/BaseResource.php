<?php namespace Twestival\Resources;

class BaseResource extends \Tonic\Resource
{
	function renderMustacheHeaderFooter($template, $data = array())
	{
		$summaryStats = $this->container['service.common']->getSummaryStats();
		
		$merged = array_merge($data, $summaryStats);
		
		$merged['CurrentYear'] = $this->container['service.year']->getMostRecentActiveYear();
		
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
	
	function hashToList($hash)
	{
		$array = array();
		foreach($hash as $key => $value)
		{
			array_push($array, array(
			'Key' => $key,
			'Value' => $value
			));
		}
		return $array;
	}
	
	function valuesToHashes($values)
	{
		$array = array();
		foreach($values as $value)
		{
			array_push($array, array(
				'Value' => $value
			));
		}
		return $array;
	}
	
	function selectByField($target, $field, &$values)
	{
		foreach($values as &$value)
		{
			if($target == $value[$field])
			{
				$value['Selected'] = TRUE;
				return $value;
			}
		}
		return NULL;
	}
	
	function selectByFieldFuzzy($target, $field, &$values)
	{
		$foundInsensitive = NULL;
		$foundMetaphone = NULL;
		$targetInsensitive = strtolower($target);
		$targetMetaphone = metaphone($target);
		foreach($values as &$value)
		{
			$fieldValue = $value[$field];
			if($target == $fieldValue)
			{
				// Case-insensitive match preferred; bail out
				$value['Selected'] = TRUE;
				return $value;
			}
			else if($targetInsensitive == strtolower($fieldValue))
			{
				$foundInsensitive = &$value;
			}
			else if($targetMetaphone == metaphone($fieldValue))
			{
				$foundMetaphone = &$value;
			}
		}
		if($foundInsensitive)
		{
			$foundInsensitive['Selected'] = TRUE;
			return $foundInsensitive;
		}
		else if($foundMetaphone)
		{
			$foundMetaphone['Selected'] = TRUE;
			return $foundMetaphone;
		}
		return NULL;
	}
}
?>