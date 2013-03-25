<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/registration/{registrationID}
 */
class GlobalAdminRegistrationItemResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function view($registrationID)
	{
		$registration = $this->container['service.registration']->get($registrationID);
		$relatedEvents = $this->container['service.event']->findPriorRelatedToRegistration($registrationID);
		
		$registration['RelatedEvents'] = $relatedEvents;
		$registration['HasRelatedEvents'] = !empty($relatedEvents);
		return $this->renderMustacheHeaderFooter('Global/Admin/Registration/View', 
				$registration
		);
	}
}
?>