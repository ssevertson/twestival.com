<?php namespace Twestival\Resources\Helpers;

class BaseHelper
{
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