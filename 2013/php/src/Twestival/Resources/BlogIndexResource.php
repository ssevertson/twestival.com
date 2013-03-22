<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /index
 */
class BlogIndexResource extends BaseResource
{
	/**
	 * @method get
	 */
	function html() {
		return $this->renderMustacheHeaderFooter('Header');
	}
}
?>