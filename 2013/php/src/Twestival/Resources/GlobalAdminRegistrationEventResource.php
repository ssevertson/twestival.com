<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/registration/{registrationID}/event
 */
class GlobalAdminRegistrationEventResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function showEditor($registrationID)
	{
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();

		$registration = $this->container['service.registration']->get($registrationID);
		
		$event = $this->container['service.event']->getDefaultEventSettings($currentYear, $registration);
				
		$blogs = $this->container['service.blog']->getUnassigned();
		if($event['BlogID'])
		{
			$this->selectByField($event['BlogID'], 'BlogID', $blogs);
		}
		$event['AvailableBlogs'] = $blogs;
		
		return $this->renderMustacheHeaderFooter('Global/Admin/Registration/Event', array(
				'Registration' => $registration,
				'Event' => $event
		));
	}
	
	/**
	 * @method put
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function save($registrationID)
	{
		$this->container['service.registration']->approve(intval($registrationID));
		
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();
		
		$blogID = 0;
		if($_POST['BlogSubdomain'])
		{
			$blogID = $this->container['service.blog']->create($_POST['BlogSubdomain']);
		}
		else
		{
			
			$blogID = intval($_POST['BlogID']);
		}
		
		$eventID = $this->container['service.event']->create(
			intval($registrationID),
			$blogID,
			$currentYear,
			$_POST['Name'],
			$_POST['Description'],
			$_POST['TwitterName'],
			$_POST['OrganizerEmailAddress'],
			'default.png'
		);
		
		
		
		throw new \Twestival\RedirectException("/admin/event/$eventID");
	}
}
?>