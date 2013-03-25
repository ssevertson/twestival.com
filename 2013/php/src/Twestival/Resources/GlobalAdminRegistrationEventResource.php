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
		$registration = $this->container['service.registration']->get($registrationID);
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();
		$blogs = $this->container['service.blog']->getUnassignedBlogs($currentYear);
		$relatedEvents = $this->container['service.event']->findPriorRelatedToRegistration($registrationID);
		
		$registration['RelatedEvents'] = $relatedEvents;
		$registration['HasRelatedEvents'] = !empty($relatedEvents);
		return $this->renderMustacheHeaderFooter('Global/Admin/Registration/View', 
				$registration
		);
	}
}
?>