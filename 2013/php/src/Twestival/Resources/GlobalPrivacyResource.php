<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /privacy
 */
class GlobalPrivacyResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Privacy');
	}
}
?>