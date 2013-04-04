<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /impact
 */
class GlobalImpactResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Impact');
	}
}
?>