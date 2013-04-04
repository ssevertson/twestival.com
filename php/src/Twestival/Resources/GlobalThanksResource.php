<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /thanks/{type}
 */
class GlobalThanksResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function thank($type)
	{
		return $this->renderMustacheHeaderFooter('Global/Thanks', array(
				'Message' => $this->container['service.thankyou']->getMessage($type)
		));
	}
}
?>