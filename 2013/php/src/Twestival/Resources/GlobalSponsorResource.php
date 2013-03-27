<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /sponsor
 */
class GlobalSponsorResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Sponsor');
	}
}
?>