<?php namespace Twestival\Services;

use Twestival\DAOs\EventsDAO;

class GlobalStatsService extends BaseService
{
	function getSummaryStats()
	{
		$events = new EventsDAO($this->container);
		return array(
			'CityCount' => $events->countEventLocationsByType('CITY'),
			'CountryCount' => $events->countEventLocationsByType('COUNTRY'),
			'DonationTotalUSD' => $events->sumEventDonationTotalUSD()
		);
	}
}
?>