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
		$eventPromotions = $this->container['service.promotion']->getListForceCount($pageName, $sectionName, array(''));
		$eventPromotions[0]['First'] = TRUE;
		$eventPromotions[count($eventPromotions) - 1]['Last'] = TRUE;
		
		// Sequences are assigned by PromotionService, but we only want add links to create the *next* valid sequence
		$maxValidSequence = 0;
		foreach($eventPromotions as &$eventPromotion)
		{
			if($eventPromotion['Name'])
			{
				$maxValidSequence = $eventPromotion['Sequence'];
			}
			else
			{
				$eventPromotion['Sequence'] = $maxValidSequence + 1;
			}
		}
		
		return $this->renderMustacheHeaderFooter('Global/Admin/EventPromotion/List', array(
				'PageName' => $pageName,
				'SectionName' => $sectionName,
				'EventPromotions' => $eventPromotions
		));
	}
}
?>