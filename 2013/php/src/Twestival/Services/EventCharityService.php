<?php namespace Twestival\Services;

class EventCharityService extends BaseService
{
	function getCharities($eventID)
	{
		$charities = $this->container['dao.event.charities']->items($eventID);
		$this->addUrisToCharities($charities);
		return $charities;
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
		$event['ImageUri'] = $this->getImageUri($charity['ImageFilename']);
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