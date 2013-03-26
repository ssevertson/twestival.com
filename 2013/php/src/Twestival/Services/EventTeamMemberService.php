<?php namespace Twestival\Services;

use Twestival\DAOs\EventTeamMembersDAO;

class EventTeamMemberService extends BaseService
{
	function getTeamMembers($eventID)
	{
		return $this->container['dao.event.teamMembers']->items($eventID);
	}
	
	function countTeamMembers($eventID)
	{
		return $this->container['dao.event.teamMembers']->count($eventID);
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