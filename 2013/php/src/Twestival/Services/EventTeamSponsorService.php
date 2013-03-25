<?php namespace Twestival\Services;

use Twestival\DAOs\EventTeamSponsorsDAO;

class EventTeamSponsorService extends BaseService
{
	function getEventTeamSponsors() {
		$blogs = new EventTeamSponsorsDAO($this->container);
		return $blogs->getEventTeamSponsors();
	}	

	function getEventTeamSponsor($anEventID = '') {
		$blogs = new EventTeamSponsorsDAO($this->container);
		return $blogs->getEventTeamSponsor($anEventID);
	}
}
?>