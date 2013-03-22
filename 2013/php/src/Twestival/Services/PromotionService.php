<?php namespace Twestival\Services;

use Twestival\DAOs\EventsDAO;

class PromotionService extends BaseService
{
	function getForPageSection($pageName, $sectionName, $count, $force = FALSE)
	{
		$protocol = $this->container['request.protocol'];
		$hostname = $this->container['request.hostname'];
		$domain = $this->container['request.domain'];
		$baseUri = $this->container['baseUri'];
		$imagePath = $baseUri . '/img/' . strtolower($pageName) . '/promotion/' . strtolower($sectionName) . '/content';

		$events = new EventsDAO($this->container);
		$eventPromotions = array();
		foreach ($events->getPromotionsForPageSection($pageName, $sectionName, $count) as $eventPromotion)
		{
			array_push($eventPromotions, array(
				'Sequence' => count($eventPromotions) + 1,
				'Name' => $eventPromotion->PromotionName,
				'ImageUri' => $protocol . $hostname . $imagePath . '/' .$eventPromotion->PromotionImageFilename,
				'LinkUri' => $protocol . $eventPromotion->Subdomain . '.' . $domain . '/',
				'Date' => $eventPromotion->Date
			));
		}
		if($force)
		{
			while(count($eventPromotions) < $count)
			{
				array_push($eventPromotions, array(
					'Sequence' => count($eventPromotions) + 1,
					'Name' => '',
					'ImageUri' => '',
					'LinkUri' => '',
					'Date' => 0
				));
			}
		}
		return $eventPromotions;
	}
}
?>