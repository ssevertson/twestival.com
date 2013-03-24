<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/event-promotions/{pageName}/{sectionName}
 */
class GlobalAdminEventPromotionListResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function showList($pageName, $sectionName)
	{
		$eventPromotions = $this->container['service.promotion']->getList($pageName, $sectionName, TRUE);
		$eventPromotions[0]['First'] = TRUE;
		$eventPromotions[count($eventPromotions) - 1]['Last'] = TRUE;
		return $this->renderMustacheHeaderFooter('GlobalAdminEventPromotionList', array(
				'PageName' => $pageName,
				'SectionName' => $sectionName,
				'EventPromotions' => $eventPromotions
		));
	}
}
?>