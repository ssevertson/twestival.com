<?php namespace Twestival;

class RedirectException extends \Exception
{
    protected $uri;
    
	function __construct($uri = '/')
	{
		$this->uri = $uri;
	}
    
    function getUri()
    {
    	return $this->uri;
    } 
}
