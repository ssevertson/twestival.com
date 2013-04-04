<?php namespace Twestival\Services;

class RegistrationService extends BaseService
{
	function save($reRegistration, $name, $twitterName, $emailAddress, $city, $stateProvince, $country, $preferredTwestivalName, $charityDescription, $comment)
	{
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();
		
		$registrationID = $this->container['dao.registrations']->create(
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
		
		$this->container['email.mailer']->send(
				$this->container['email.message']
						->setSubject('Twestival Registration Confirmation')
						->setBody(
								$this->container['mustache.engine']->loadTemplate('Email/RegistrationConfirmation')->render()
						)
						->setTo(array(
								$emailAddress => $name
						))
		);
		$this->container['email.mailer']->send(
				$this->container['email.message']
				->setSubject('New Registration')
				->setBody(
						$this->container['mustache.engine']->loadTemplate('Email/RegistrationCreated')->render(array(
								'Name' => $name,
								'TwitterName' => $twitterName,
								'City' => $city,
								'StateProvince' => $stateProvince,
								'Country' => $country,
								'Uri' => 'http://' . $this->container['request.subdomain.global'] . '.' . $this->container['request.domain'] . $this->container['baseUri'] . '/admin/registration/' . $registrationID
						))
				)
				->setTo(array(
						'registrations@twestival.com' => 'Twestival Registration'
				))
		);
		
		return $registrationID;
	}
	
	function deny($registrationID)
	{
		$this->container['dao.registrations']->setApprovalStatus(
				$registrationID,
				'Denied'
		);
	}
	function approve($registrationID)
	{
		$this->container['dao.registrations']->setApprovalStatus(
				$registrationID,
				'Approved'
		);
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