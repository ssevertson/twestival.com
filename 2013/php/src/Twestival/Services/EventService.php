<?php namespace Twestival\Services;

class EventService extends BaseService
{
	function getByContinent($year)
	{
		return $this->container['dao.events']->itemsByLocationName($year, 'CONTINENT');
	}
}
?>