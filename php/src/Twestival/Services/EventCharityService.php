<?php namespace Twestival\Services;

class EventCharityService extends BaseService
{
	const IMAGE_FILENAME_PREFIX = 'charity-';
	
	function getCharities($eventID)
	{
		$charities = $this->container['dao.event.charities']->items($eventID);
		$this->addUrisToCharities($charities);
		return $charities;
	}
	function update($eventID, $eventCharityID, $name, $uri, $imageFilename)
	{
		return $this->container['dao.event.charities']->update($eventID, $eventCharityID, $name, $uri, $imageFilename);
	}
	function create($eventID, $name, $uri, $imageFilename)
	{
		return $this->container['dao.event.charities']->create($eventID, $name, $uri, $imageFilename);
	}
	
	private function addUrisToCharities(&$charities)
	{
		foreach($charities as &$charity)
		{
			$this->addUrisToCharity($charity);
		}
	}
	private function addUrisToCharity(&$charity)
	{
		$charity['ImageUri'] = $this->getImageUri($charity['ImageFilename']);
	}
	function getImagePath()
	{
		return 'img/event/content';
	}
	function getImageUri($imageFilename = '')
	{
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