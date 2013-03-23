<?php namespace Twestival;

class RedirectException extends \Exception
{
    protected $uri  = '/';
    protected $temporary = FALSE;
    
    function getUri()
    {
    	return $this->uri;
    } 
	function getTemporary()
	{
		return $this->temporary;
	} 
}
