<?php namespace Twestival;

class RedirectException extends \Exception
{
    protected $uri  = '/';
    protected $temporary = TRUE;
}
