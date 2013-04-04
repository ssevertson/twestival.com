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
		
		$registration['ApprovalStatusNew'] = ($registration['ApprovalStatus'] == 'New');
		
		return $this->renderMustacheHeaderFooter('Global/Admin/Registration/View', 
				$registration
		);
	}
	
	/**
	 * @method put
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function changeApprovalStatus($registrationID)
	{
		$approvalStatus = isset($_POST['ApprovalStatus'])
				? $_POST['ApprovalStatus']
				: '';
		
		switch(strtoupper($approvalStatus))
		{
			case 'APPROVE':
				// Further data needed before creating Event
				throw new \Twestival\RedirectException("/admin/registration/$registrationID/event");
			case 'DENY':
				$this->container['service.registration']->deny(intval($registrationID));
				throw new \Twestival\RedirectException('/admin/registration');
			default:
				throw new \Twestival\RedirectException('/admin/registration');
		}
	}
}
?>