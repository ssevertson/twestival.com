<?php namespace Twestival\DAOs;

class BaseDAO
{
	protected $container;
	function __construct($container)
	{
		$this->container = $container;
	}
	
	function trimToNull($value)
	{
		$value = trim($value);
		return $value ? $value : NULL;
	}
	function toBoolean($value)
	{
		return $value ? TRUE : FALSE;
	}
	function toDate($value)
	{
		return $value ? date('Y-m-d', $value) : NULL;
	}
}
?>