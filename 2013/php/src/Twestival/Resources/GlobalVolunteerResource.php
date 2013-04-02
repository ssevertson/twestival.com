<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /volunteer
 */
class GlobalVolunteerResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();
		$continents = $this->container['service.event']->getByContinent($currentYear);
		
		return $this->renderMustacheHeaderFooter('Global/Volunteer', array(
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
		
		$subject = 'New Unassigned Volunteer';
		$eventName = '';
		if($eventID)
		{
			$subject = 'New Volunteer';
			$event = $this->container['service.event']->getEvent($eventID);
			$eventName = $event['Name'];
				
			$this->container['service.email']->send(
					array($event['OrganizerEmailAddress'] => $event['Name']),
					$subject,
					$this->renderMustacheHeaderFooter('Email/Volunteer', array(
							'Name' => $_POST['Name'],
							'TwitterName' => $_POST['TwitterName'],
							'EmailAddress' => $_POST['EmailAddress'],
							'Capacity' => $_POST['Capacity']
					))
			);
		}
		$this->container['service.email']->send(
				'volunteer@twestival.com',
				$subject,
				$this->renderMustacheHeaderFooter('Email/Volunteer', array(
						'EventName' => $eventName,
						'City' => $_POST['City'],
						'StateProvince' => $_POST['StateProvince'],
						'Country' => $_POST['Country'],
						'Name' => $_POST['Name'],
						'TwitterName' => $_POST['TwitterName'],
						'EmailAddress' => $_POST['EmailAddress'],
						'Capacity' => $_POST['Capacity']
				))
		);
		throw new \Twestival\RedirectException('/thanks/volunteer');
	}
}
?>