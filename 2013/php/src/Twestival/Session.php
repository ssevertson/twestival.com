<?php namespace Twestival;

class Session implements \ArrayAccess
{
	public function offsetSet($name, $value)
	{
		$_SESSION[$name] = $value;
	}
	public function __set($name, $value)
	{
		$_SESSION[$name] = $value;
	}
	public function offsetGet($name)
	{
		return $_SESSION[$name];
	}
	public function __get($name)
	{
		return $_SESSION[$name];
	}
	public function offsetExists($name)
	{
		return isset($_SESSION[$name]);
	}
	public function __isset($name)
	{
		return isset($_SESSION[$name]);
	}
	public function offsetUnset($name)
	{
		unset($_SESSION[$name]);
	}
	public function __unset($name)
	{
		unset($_SESSION[$name]);
	}
}
?>