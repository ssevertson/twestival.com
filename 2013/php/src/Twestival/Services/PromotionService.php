<?php namespace Twestival\Services;

class PromotionService extends BaseService
{
	const MAX_SEQUENCE = 255;
	function getMaxEvents($pageName, $sectionName)
	{
		$promotions = $this->container['dao.promotions'];;
		return $promotions->getMaxEvents($pageName, $sectionName);
	}
	
	function getImagePath($pageName, $sectionName)
	{
		return 'img/' . strtolower($pageName) . '/promotion/' . strtolower($sectionName) . '/content';
	}
	
	function getImageUri($pageName, $sectionName, $imageFilename = '')
	{
		return $this->container['request.protocol']
				. $this->container['request.hostname']
				. $this->container['baseUri']
				. '/' 
				. $this->getImagePath($pageName, $sectionName)
				. '/' 
				. $imageFilename;
	}
	
	function getList($pageName, $sectionName, $force = FALSE)
	{
		$promotions = $this->container['dao.promotions'];
		$count = $this->getMaxEvents($pageName, $sectionName);
		
		$eventPromotions = array();
		foreach ($promotions->items($pageName, $sectionName, $count) as $eventPromotion)
		{
			array_push($eventPromotions, array(
				'PageName' => $pageName,
				'SectionName' => $sectionName,
				'Sequence' => $eventPromotion['Sequence'],
				'Name' => $eventPromotion['Name'],
				'ImageFilename' => $eventPromotion['ImageFilename'],
				'ImageUri' => $this->getImageUri($pageName, $sectionName, $eventPromotion['ImageFilename']),
				'LinkUri' => $this->container['request.protocol'] . $eventPromotion['Subdomain'] . '.' . $this->container['request.domain'] . '/',
				'Date' => $eventPromotion['EventDate']
			));
		}
		if($force)
		{
			while(count($eventPromotions) < $count)
			{
				array_push($eventPromotions, array(
					'PageName' => $pageName,
					'SectionName' => $sectionName,
					'Sequence' => count($eventPromotions) + 1,
					'Name' => '',
					'ImageFilename' => '',
					'ImageUri' => '',
					'LinkUri' => '',
					'Date' => 0
				));
			}
		}
		return $eventPromotions;
	}
	
	function get($pageName, $sectionName, $sequence)
	{
		$promotions = $this->container['dao.promotions'];
		
		$eventPromotion = $promotions->get($pageName, $sectionName, $sequence);
		if($eventPromotion)
		{
			return array(
					'PageName' => $pageName,
					'SectionName' => $sectionName,
					'Sequence' => $sequence,
					'EventID' => $eventPromotion['EventID'],
					'Name' => $eventPromotion['Name'],
					'ImageFilename' => $eventPromotion['ImageFilename'],
					'ImageUri' => $this->getImageUri($pageName, $sectionName, $eventPromotion['ImageFilename'])
			);
		}
		else
		{
			return array(
					'PageName' => $pageName,
					'SectionName' => $sectionName,
					'Sequence' => $sequence,
					'EventID' => 0,
					'Name' => '',
					'ImageUri' => '',
					'ImageFilename' => ''
			);
		}
	}

	function moveUp($pageName, $sectionName, $sequence)
	{
		$this->move($pageName, $sectionName, $sequence, -1);
	}
	function moveDown($pageName, $sectionName, $sequence)
	{
		$this->move($pageName, $sectionName, $sequence, 1);
	}
	private function move($pageName, $sectionName, $sequence, $offset)
	{
		$promotions = $this->container['dao.promotions'];
		
		$promotions->updateSequence($pageName, $sectionName, $sequence, PromotionService::MAX_SEQUENCE);
		$promotions->updateSequence($pageName, $sectionName, $sequence + $offset, $sequence);
		$promotions->updateSequence($pageName, $sectionName, PromotionService::MAX_SEQUENCE, $sequence + $offset);
	}
	
	function save($pageName, $sectionName, $sequence, $eventID, $name, $imageFilename)
	{
		$promotions = $this->container['dao.promotions'];
		
		$eventPromotion = $promotions->get($pageName, $sectionName, $sequence);
		if($eventPromotion)
		{
			$promotions->update($pageName, $sectionName, $sequence, $eventID, $name, $imageFilename);
		}
		else
		{
			$promotions->create($pageName, $sectionName, $sequence, $eventID, $name, $imageFilename);
		}
	}
	function delete($pageName, $sectionName, $sequence)
	{
		$promotions = $this->container['dao.promotions'];
		$promotions->delete($pageName, $sectionName, $sequence);
		
		$count = $this->getMaxEvents($pageName, $sectionName);
		foreach ($promotions->items($pageName, $sectionName, $count) as $eventPromotion)
		{
			if($eventPromotion['Sequence'] > $sequence)
			{
				$promotions->updateSequence($pageName, $sectionName, $eventPromotion['Sequence'], $eventPromotion['Sequence'] - 1);
			}
		}
	}
}
?>