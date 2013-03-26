<?php namespace Twestival\Services;

use Twestival\DAOs\EventTeamSponsorsDAO;

class EventSponsorService extends BaseService
{
	function getSponsors($eventID)
	{
		return $this->container['dao.event.sponsors']->items($eventID);
	}
	
	function countSponsors($eventID)
	{
		return $this->container['dao.event.sponsors']->count($eventID);
	}
	
	function save($eventID, $sequence, $name, $uri, $imageFilename)
	{
		$sponsors = $this->container['dao.event.sponsors'];
	
		$sponsor = $sponsors->get($eventID, $sequence);
		if($sponsor)
		{
			$sponsors->update($eventID, $sequence, $name, $uri, $imageFilename);
		}
		else
		{
			$sponsors->create($eventID, $sequence, $name, $uri, $imageFilename);
		}
	}
}
?>