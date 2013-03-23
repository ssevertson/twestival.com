<?php namespace Twestival\Services;

use Twestival\DAOs\EventsDAO;
use Twestival\DAOs\YearsDAO;

class CommonService extends BaseService
{
	function getSummaryStats()
	{
		$events = $this->container['dao.events'];
		return array(
			'CityCount' => $events->countEventLocationsByType('CITY'),
			'CountryCount' => $events->countEventLocationsByType('COUNTRY'),
			'DonationTotalUSD' => $events->sumEventDonationTotalUSD()
		);
	}
	
	function getMostRecentActiveYear()
	{
		$years = $this->container['dao.years'];
		return $years->getMostRecentActiveYear();
	}
}
?>