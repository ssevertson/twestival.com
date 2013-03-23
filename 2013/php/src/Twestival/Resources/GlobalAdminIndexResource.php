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
		$promotions = new PromotionService($this->container);
		$pages = new PageService($this->container);
		
		return $this->renderMustacheHeaderFooter('GlobalAdminIndex', array(
				'PageSections' => $promotions->getPageSections(),
				'PageContents' => $pages->getPageContents()
		));
	}
}
?>