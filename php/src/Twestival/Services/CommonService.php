<?php namespace Twestival\Services;

class CommonService extends BaseService
{
	const BASELINE_YEAR = 2013;
	const BASLINE_DONATION_TOTAL = 1750000;
	const BASLINE_CHARITY_COUNT = 285;
	
	function getActiveYearSummaryStats()
	{
		$events = $this->container['dao.events'];
		return array(
			'CityCount' => $events->countEventLocationsByType('CITY'),
			'CountryCount' => $events->countEventLocationsByType('COUNTRY'),
			'DonationTotalUSD' => $events->sumEventDonationTotalUSD()
		);
	}
	
	function getRunningSummaryStats()
	{
		return array(
			'CharityCount' => CommonService::BASLINE_CHARITY_COUNT + $this->container['dao.event.charities']->countRunningEventCharities(CommonService::BASELINE_YEAR),
			'DonationTotalUSD' => CommonService::BASLINE_DONATION_TOTAL + $this->container['dao.events']->sumRunningEventDonationTotalUSD(CommonService::BASELINE_YEAR)
		);
	}
}
?>