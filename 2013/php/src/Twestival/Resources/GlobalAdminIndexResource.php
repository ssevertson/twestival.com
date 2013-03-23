<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin
 */
class GlobalAdminIndexResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function showMenu()
	{
		return $this->renderMustacheHeaderFooter('GlobalAdminIndex', array(
				'PageSections' => $this->container['service.promotion']->getPageSections(),
				'PageContents' => $this->container['service.page']->getPageContents()
		));
	}
}
?>