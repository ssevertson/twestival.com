<?php namespace Twestival\Resources;

use Twestival\Services\EventTeamSponsorService;
/**
 * @namespace global
 * @uri /eventteamsponsors
 * @uri /eventteamsponsors/:id

 */
class EventTeamSponsorResource extends BaseResource
{
	/**
	 * @method get
     * @param  int $anEventID
     * @return str
	 */
	function html($anEventID = '') {
		$eventteamsponsorService = new EventTeamSponsorService($this->container);
		
		if ($anEventID == '')
			return $eventteamsponsorService->getEventTeamSponsors();
		else {
			return $eventteamsponsorService->getEventTeamSponsor($anEventID);
		}
	}

}
?>