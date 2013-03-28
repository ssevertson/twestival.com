<?php namespace Twestival\Services;

class EventSponsorService extends BaseService
{
	function getSponsors($eventID)
	{
		$sponsors = $this->container['dao.event.sponsors']->items($eventID);
		$this->addUrisToSponsors($sponsors);
		return $sponsors;
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