<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/event-promotions/{pageName}/{sectionName}/{sequence}
 */
class GlobalAdminEventPromotionItemResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function showEditor($pageName, $sectionName, $sequence)
	{
		$eventPromotion = $this->container['service.promotion']->get($pageName, $sectionName, intval($sequence));
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();
		
		$continents = $this->container['service.event']->getByContinent($currentYear);
		$this->selectEvent($eventPromotion['EventID'], $continents);
		$eventPromotion['Continents'] = $this->hashToList($continents);
		
		return $this->renderMustacheHeaderFooter('Global/Admin/EventPromotion/Edit', 
				$eventPromotion
		);
	}
	
	private function selectEvent($eventID, &$continents)
	{
		foreach($continents as $continent => &$events)
		{
			foreach($events as &$event)
			{
				if($eventID == $event['EventID'])
				{
					$event['Selected'] = TRUE;
					return;
				}
			}
		}
	}
	
	/**
	 * @method post
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function modify($pageName, $sectionName, $sequence)
	{
		if($_POST['Operation'] == '↑')
		{
			$this->container['service.promotion']->moveUp($pageName, $sectionName, intval($sequence));
		}
		else if($_POST['Operation'] == '↓')
		{
			$this->container['service.promotion']->moveDown($pageName, $sectionName, intval($sequence));
		}
		throw new \Twestival\RedirectException("/admin/event-promotions/$pageName/$sectionName");
	}
	/**
	 * @method delete
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function delete($pageName, $sectionName, $sequence)
	{
		$this->container['service.promotion']->delete($pageName, $sectionName, intval($sequence));
		throw new \Twestival\RedirectException("/admin/event-promotions/$pageName/$sectionName");
	}
	
	/**
	 * @method put
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function save($pageName, $sectionName, $sequence)
	{
		$upload = new Files\EventPromotionUpload($this->container, $pageName, $sectionName, 'Image');
		$result = $upload->process($_FILES);
		
		$imageFilename = $_POST['ImageFilename'];
		if(isset($result['Image']) && isset($result['Image']['Filename']))
		{
			$imageFilename = $result['Image']['Filename'];
		}
		
		$this->container['service.promotion']->save(
				$pageName,
				$sectionName,
				intval($sequence),
				$_POST['EventID'],
				$_POST['Name'],
				$imageFilename);
		throw new \Twestival\RedirectException("/admin/event-promotions/$pageName/$sectionName");
	}
}
?>