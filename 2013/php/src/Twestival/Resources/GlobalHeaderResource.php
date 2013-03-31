<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /header
 */
class GlobalHeaderResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Header');
	}
}
?>