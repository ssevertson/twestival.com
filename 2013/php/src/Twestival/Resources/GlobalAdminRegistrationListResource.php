<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/registration
 */
class GlobalAdminRegistrationListResource extends BaseResource
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
		
		$statuses = $this->valuesToHashes($this->container['service.registration']->getStatuses());
		$status = isset($_GET['Status'])
				? $_GET['Status']
				: 'New';
		$this->selectByField($status, 'Value', $statuses);
		
		$registrations = $this->container['service.registration']->getList($year, $status);
		
		return $this->renderMustacheHeaderFooter('Global/Admin/Registration/List', array(
				'Years' => $years,
				'Statuses' => $statuses,
				'Registrations' => $registrations
		));
	}
}
?>