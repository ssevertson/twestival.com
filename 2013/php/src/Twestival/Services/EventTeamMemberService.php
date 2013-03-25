<?php namespace Twestival\Services;

use Twestival\DAOs\EventTeamMembersDAO;

class EventTeamMemberService extends BaseService
{
	function getTeamMembers($eventID)
	{
		return json_encode($this->container['dao.event.teamMembers']->items($eventID));
	}
}
?>