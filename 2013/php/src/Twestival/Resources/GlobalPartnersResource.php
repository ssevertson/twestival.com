<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /partners
 */
class GlobalPartnersResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Partners');
	}
}
?>