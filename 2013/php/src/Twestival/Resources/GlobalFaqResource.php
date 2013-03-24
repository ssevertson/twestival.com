<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /faq
 */
class GlobalFaqResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Editable/Faq', array(
			'PageContent' => $this->container['service.page']->getPageContent('FAQ', 'BODY')
		));
	}
}
?>