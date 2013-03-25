<?php namespace Twestival\Services;

use Twestival\DAOs\BlogsDAO;

class EventCharityService extends BaseService
{
	function getCharities($eventID)
	{
		return $this->container['dao.event.charities']->items($eventID);
	}
}
?>