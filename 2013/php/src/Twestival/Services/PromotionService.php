<?php namespace Twestival\Services;

use Twestival\DAOs\EventsDAO;

class PromotionService extends BaseService
{
	function getForPageSection($pageName, $sectionName)
	{
		$protocol = $this->container['request.protocol'];
		$hostname = $this->container['request.hostname'];
		$domain = $this->container['request.domain'];
		$baseUri = $this->container['baseUri'];
		$imagePath = $baseUri . '/img/' . strtolower($pageName) . '/promotion/' . strtolower($sectionName) . '/content';

		$events = new EventsDAO($this->container);
		$eventPromotions = array();
		foreach ($events->getPromotionsForPageSection($pageName, $sectionName) as $eventPromotion)
		{
			array_push($eventPromotions, array(
				'Sequence' => $eventPromotion->PromotionSequence,
				'Name' => $eventPromotion->PromotionName,
				'ImageUri' => $protocol . $hostname . $imagePath . '/' .$eventPromotion->PromotionImageFilename,
				'LinkUri' => $protocol . $eventPromotion->Subdomain . '.' . $domain . '/',
				'Date' => $eventPromotion->Date
			));
		}
		return $eventPromotions;
	}
}
?>