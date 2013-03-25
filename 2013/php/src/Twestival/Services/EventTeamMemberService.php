<?php namespace Twestival\Services;

use Twestival\DAOs\EventTeamMembersDAO;

class EventTeamMemberService extends BaseService
{
	function getEventTeamMembers() {
		$blogs = new EventTeamMembersDAO($this->container);
		return $blogs->getEventTeamMembers();
	}

	function getEventTeamMember($anEventID = '') {
		$blogs = new EventTeamMembersDAO($this->container);
		return $blogs->getEventTeamMember($anEventID);
	}
}
?>