<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /footer
 */
class GlobalFooterResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Footer');
	}
}
?>