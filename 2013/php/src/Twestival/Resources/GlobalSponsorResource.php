<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /sponsor
 */
class GlobalSponsorResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();
		$continents = $this->container['service.event']->getByContinent($currentYear);
		
		return $this->renderMustacheHeaderFooter('Global/Sponsor', array(
				'Continents' => $this->hashToList($continents)
		));
	}
	

	/**
	 * @method post
	 * @provides text/html
	 */
	function save()
	{
		$eventID = intval($_POST['EventID']);
	
		$subject = 'Unassigned Sponsorship Request';
		$eventName = '';
		if($eventID)
		{
			$subject = 'Sponsorship Request';
			$event = $this->container['service.event']->getEvent($eventID);
			$eventName = $event['Name'];
			$this->container['service.email']->send(
					array($event['OrganizerEmailAddress'] => $eventName),
					$subject,
					$this->renderMustacheHeaderFooter('Email/Sponsor', array(
							'Name' => $_POST['Name'],
							'Type' => $_POST['Type'],
							'Organization' => $_POST['Organization'],
							'PhoneNumber' => $_POST['PhoneNumber'],
							'EmailAddress' => $_POST['EmailAddress'],
							'Uri' => $_POST['Uri'],
							'Details' => $_POST['Details']
					))
			);
		}
		
		$this->container['service.email']->send(
				'sponsorship@twestival.com',
				$subject,
				$this->renderMustacheHeaderFooter('Email/Sponsor', array(
						'EventName' => $eventName,
						'City' => $_POST['City'],
						'StateProvince' => $_POST['StateProvince'],
						'Country' => $_POST['Country'],
						'Type' => $_POST['Type'],
						'Organization' => $_POST['Organization'],
						'Name' => $_POST['Name'],
						'PhoneNumber' => $_POST['PhoneNumber'],
						'EmailAddress' => $_POST['EmailAddress'],
						'Uri' => $_POST['Uri'],
						'Details' => $_POST['Details']
				))
		);
		
		throw new \Twestival\RedirectException('/thanks/sponsor');
	}
}
?>