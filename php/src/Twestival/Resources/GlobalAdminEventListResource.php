<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/event
 */
class GlobalAdminEventListResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function showList()
	{
		$years = $this->container['service.year']->getYears();
		$year = isset($_GET['Year'])
				? $_GET['Year']
				: $this->container['service.year']->getMostRecentActiveYear();
		$this->selectByField($year, 'Year', $years);
		
		$active = isset($_GET['Active'])
				? $_GET['Active'] === 'true'
				: 'true';
		
		$events = $this->container['service.event']->getEvents($year, $active);
		
		return $this->renderMustacheHeaderFooter('Global/Admin/Event/List', array(
				'Years' => $years,
				'Active' => $active,
				'Events' => $events
		));
	}
	/**
	 * @method post
	 * @requireSiteAdmin
	 */
	function updateLocations()
	{
		foreach($this->container['service.year']->getYears() as $year)
		{
			$this->container['logger']->addInfo('Processing Year: ' . $year);
			foreach($this->container['service.event']->getEvents($year['Year'], TRUE) as $event)
			{
				if($event['LocationID'])
				{
					$this->container['logger']->addInfo('Updating Event: ' . $event['EventID']);
					$this->container['service.location']->saveEventLocationCity($event['EventID'], $event['LocationID']);
				}
			}
		}
		throw new \Twestival\RedirectException("/admin");
	}
}
?>