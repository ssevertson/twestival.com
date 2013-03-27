<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /get_involved
 */
class GlobalGetInvolvedResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/GetInvolved');
	}
}
?>