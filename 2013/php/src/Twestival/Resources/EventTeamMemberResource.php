<?php namespace Twestival\Resources;

use Twestival\Services\EventTeamMemberService;
/**
 * @namespace global
 * @uri /eventteammembers/{eventID}

 */
class EventTeamMemberResource extends BaseResource
{
	/**
	 * @method get
	 * @provides application/json
	 */
	function json($eventID) {
		$teamMembers = $this->container['service.event.teamMembers'];
		return json_encode($teamMembers->getEventTeamMembers($eventID));
	}

}
?>