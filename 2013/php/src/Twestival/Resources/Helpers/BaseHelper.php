<?php namespace Twestival\Resources\Helpers;

class BaseHelper
{
	protected $container;
	function __construct($container)
	{
		$this->container = $container;
	}
	
	public function __isset($key)
	{
		return method_exists($this, '_'.$key);
	}

	public function __get($key)
	{
		return array($this, '_'.$key);
	}
}
?>