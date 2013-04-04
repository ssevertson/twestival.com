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
}
?>