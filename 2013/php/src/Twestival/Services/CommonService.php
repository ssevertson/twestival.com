<?php namespace Twestival\Services;

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
		return 2011; //$years->getMostRecentActiveYear();
	}
}
?>