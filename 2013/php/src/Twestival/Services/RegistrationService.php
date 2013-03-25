<?php namespace Twestival\Services;

class RegistrationService extends BaseService
{
	function save($reRegistration, $name, $twitterName, $emailAddress, $city, $stateProvince, $country, $preferredTwestivalName, $charityDescription, $comment)
	{
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();
		
		$this->container['dao.registrations']->create(
			$currentYear,
			$reRegistration,
			$name,
			$twitterName,
			$emailAddress,
			$city,
			$stateProvince,
			$country,
			$preferredTwestivalName,
			$charityDescription,
			$comment
		);
		
		// TODO: Email notification to registration@twestival.com, with direct link to entry (/admin/registration/{RegistrationID})
	}
	
	function getNewForCurrentYear()
	{
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();
		return $this->getList($currentYear, 'New');
	}
	function getList($year, $status)
	{
		return $this->container['dao.registrations']->items($year, $status);
	}
	function get($registrationID)
	{
		return $this->container['dao.registrations']->get($registrationID);
	}
	function getStatuses()
	{
		return array('New', 'Approved', 'Denied');
	}
}
?>