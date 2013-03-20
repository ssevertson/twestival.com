<?php namespace Twestival\DAOs;

class BaseDAO
{
	protected $container;
	function __construct($container)
	{
		$this->container = $container;
	}
}
?>