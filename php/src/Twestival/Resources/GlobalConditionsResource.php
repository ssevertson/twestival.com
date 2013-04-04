<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /conditions
 */
class GlobalConditionsResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Conditions');
	}
}
?>