<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /impact/test
 */
class GlobalImpactTestResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/ImpactTest');
	}
}
?>