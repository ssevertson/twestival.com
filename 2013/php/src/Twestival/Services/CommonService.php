<?php namespace Twestival\Services;

use Twestival\DAOs\EventsDAO;
use Twestival\DAOs\YearsDAO;

class CommonService extends BaseService
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
	
	function getMostRecentActiveYear()
	{
		$years = new YearsDAO($this->container);
		return $years->getMostRecentActiveYear();
	}
}
?>