<?php namespace Twestival;

class RedirectException extends \Exception
{
    protected $uri;
    protected $temporary;
    
	function __construct($uri = '/', $temporary = FALSE)
	{
		$this->uri = $uri;
		$this->temporary = $temporary;
	}
    
    function getUri()
    {
    	return $this->uri;
    } 
	function getTemporary()
	{
		return $this->temporary;
	} 
}
