<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /index
 */
class BlogIndexResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Header');
	}
}
?>