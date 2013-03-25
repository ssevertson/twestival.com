<?php namespace Twestival\Resources;

use Twestival\Services\EventTeamMemberService;
/**
 * @namespace global
 * @uri /eventteammembers
 * @uri /eventteammembers/:id

 */
class EventTeamMemberResource extends BaseResource
{
	/**
	 * @method get
     * @param  int $anEventID
     * @return str
	 */
	function html($anEventID = '') {
		$eventteammemberService = new EventTeamMemberService($this->container);
		
		if ($anEventID == '')
			return $eventteammemberService->getEventTeamMembers();
		else {
			return $eventteammemberService->getEventTeamMember($anEventID);
		}
	}

}
?>