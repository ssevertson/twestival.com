<?php namespace Twestival\Services;

class EventSponsorService extends BaseService
{
	const MAX_SEQUENCE = 255;
	const IMAGE_FILENAME_PREFIX = 'sponsor-';
	
	function getSponsors($eventID)
	{
		$sponsors = $this->container['dao.event.sponsors']->items($eventID);
		$this->addUrisToSponsors($sponsors);
		return $sponsors;
	}
	function getSponsor($eventID, $eventSponsorID)
	{
		$sponsor = $this->container['dao.event.sponsors']->get($eventID, $eventSponsorID);
		$this->addUrisToSponsor($sponsor);
		return $sponsor;
	}
	function update($eventID, $eventSponsorID, $name, $uri, $imageFilename)
	{
		return $this->container['dao.event.sponsors']->update($eventID, $eventSponsorID, $name, $uri, $imageFilename);
	}
	function create($eventID, $name, $uri, $imageFilename)
	{
		return $this->container['dao.event.sponsors']->create($eventID, $name, $uri, $imageFilename);
	}
	function countSponsors($eventID)
	{
		return $this->container['dao.event.sponsors']->count($eventID);
	}
	
	function moveUp($eventID, $eventSponsorID)
	{
		$this->move($eventID, $eventSponsorID, -1);
	}
	function moveDown($eventID, $eventSponsorID)
	{
		$this->move($eventID, $eventSponsorID, 1);
	}
	private function move($eventID, $eventSponsorID, $offset)
	{
		$sponsors = $this->container['dao.event.sponsors'];
		$sponsor = $this->getSponsor($eventID, $eventSponsorID);
		$sequence = $sponsor['Sequence'];
	
		$sponsors->updateSequence($eventID, $sequence, EventSponsorService::MAX_SEQUENCE);
		$sponsors->updateSequence($eventID, $sequence + $offset, $sequence);
		$sponsors->updateSequence($eventID, EventSponsorService::MAX_SEQUENCE, $sequence + $offset);
	}
	function delete($eventID, $eventSponsorID)
	{
		$sponsors = $this->container['dao.event.sponsors'];
		$sponsor = $sponsors->get($eventID, $eventSponsorID);
		$sequence = $sponsor['Sequence'];
	
		$sponsors->delete($eventID, $eventSponsorID);
	
		foreach ($sponsors->items($teamMembers) as $sponsor)
		{
			if($sponsor['Sequence'] > $sequence)
			{
				$sponsors->updateSequence($eventID, $sponsor['Sequence'], $sponsor['Sequence'] - 1);
			}
		}
	}
	
	private function addUrisToSponsors(&$sponsors)
	{
		foreach($sponsors as &$sponsor)
		{
			$this->addUrisToSponsor($sponsor);
		}
	}
	private function addUrisToSponsor(&$sponsor)
	{
		$sponsor['ImageUri'] = $this->getImageUri($sponsor['ImageFilename'], $sponsor['Legacy']);
	}
	
	function getImagePath()
	{
		return 'img/event/content';
	}
	function getImageUri($imageFilename = '', $legacy = FALSE)
	{
		if($legacy)
		{
			return $this->container['request.protocol']
			. $this->container['request.hostname']
			. '/uploads/cms/resources/'
			. $imageFilename;			
		}
		return $this->container['request.protocol']
				. $this->container['request.hostname']
				. $this->container['baseUri']
				. '/'
				. $this->getImagePath()
				. '/'
				. $imageFilename;
	}
}
?>