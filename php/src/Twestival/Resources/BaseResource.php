<?php namespace Twestival\Resources;

class BaseResource extends \Tonic\Resource
{
	/**
	 * @method head
	 * @provides text/html
	 */
	function head()
	{
		return '';
	}
	
	function renderMustacheHeaderFooter($template, $data = array())
	{
		$data['CurrentYear'] = $this->container['service.year']->getMostRecentActiveYear();
		$data['SummaryStats'] = $this->container['service.common']->getActiveYearSummaryStats();
		return $this->renderMustache($template, $data);
	}
	
	function renderMustache($template, $data = array())
	{
		$data['BaseUri'] = $this->container['baseUri'];
		$data['RequestUri'] = $this->container['request.uri'];
		$data['GlobalUri'] = $this->container['request.protocol']
				. $this->container['request.subdomain.global'] 
				. '.'
				. $this->container['request.domain']
				. $this->container['baseUri'];
		return $this->container['mustache.engine']->loadTemplate($template)->render($data);
	}
	
	function isSecure()
	{
		return $this->container['request.secure'];
	}
	function requireSecure()
	{
		if(!$this->isSecure())
		{
			throw new \Twestival\RedirectException($this->container['request.secure.uri']);
		}
	}
	function isSiteAdmin()
	{
		return $this->container['security.siteAdmin'];
	}
	function requireSiteAdmin()
	{
		$this->requireSecure();
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
		$this->requireSecure();
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
	
	function toGrid(&$array, $columns)
	{
		$rows = array(array());
		$column = 0;
	
		foreach($array as &$item)
		{
			if($column >= $columns)
			{
				$column = 0;
				array_push($rows, array());
			}
			
			array_push($rows[count($rows) - 1], $item);
				
			if($column == 0)
			{
				$item['GridPosition'] = 'left';
			}
			$column++;
			if($column == $columns)
			{
				$item['GridPosition'] = 'right';
			}
		}
		return $rows;
	}
}
?>