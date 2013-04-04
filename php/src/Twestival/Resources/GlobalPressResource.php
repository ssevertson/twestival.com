<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /press
 */
class GlobalPressResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Press', array(
			'PageContent' => $this->container['service.page']->getPageContent('PRESS', 'BODY')
		));
	}
}
?>