<?php namespace Twestival\Services;

class BaseService
{
	protected $container;
	function __construct($container)
	{
		$this->container = $container;
	}
}
?>