<?php namespace Twestival\Services;

use Twestival\DAOs\EventTeamMembersDAO;

class EventTeamMemberService extends BaseService
{
	function getTeamMembers($eventID)
	{
		return json_encode($this->container['dao.event.teamMembers']->items($eventID));
	}
	
	function save($eventID, $sequence, $twitterName)
	{
		$teamMembers = $this->container['dao.event.teamMembers'];
	
		$teamMember = $teamMembers->get($eventID, $sequence);
		if($teamMember)
		{
			$teamMembers->update($eventID, $sequence, $twitterName);
		}
		else
		{
			$teamMembers->create($eventID, $sequence, $twitterName);
		}
	}
}
?>