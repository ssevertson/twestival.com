<?php namespace Twestival\Services;

class EventService extends BaseService
{
	function getByContinent($year)
	{
		return $this->container['dao.events']->itemsByLocationName($year, 'CONTINENT');
	}
	function getEvents($year, $active)
	{
		return $this->container['dao.events']->items($year, $active);
	}
	function findPriorRelatedToRegistration($registrationID)
	{
		return $this->container['dao.events']->findPriorRelatedToRegistration($registrationID);
	}
	
	function getDefaultEventSettings($year, $registration)
	{
		$name = isset($registration['PreferredTwestivalName'])
		? $registration['PreferredTwestivalName']
		: 'Twestival ' . $registration['City'] . ' ' . $year;
	
		$relatedBlogID = 0;
		$relatedEvents = $this->findPriorRelatedToRegistration($registration['RegistrationID']);
		if($relatedEvents)
		{
			$relatedBlogID = $relatedEvents[0]['BlogID'];
		}
	
		$blogSubdomain = strtolower($registration['City']);
	
		return array(
				'Name' => $name,
				'Description' => 'Uses social media for social good by connecting communities to highlight a greater cause and have a fun event.',
				'TwitterName' => $registration['TwitterName'],
				'OrganizerEmailAddress' => $registration['EmailAddress'],
				'BlogID' => $relatedBlogID,
				'BlogSubdomain' => $blogSubdomain
		);
	}
	
	function create($registrationID, $blogID, $currentYear, $name, $description, $twitterName, $organizerEmailAddress, $imageFilename)
	{
		$eventID = $this->container['dao.events']->create(
				$registrationID,
				$blogID,
				$currentYear,
				$name,
				$description,
				$twitterName,
				$organizerEmailAddress,
				$imageFilename);
		

		$registration = $this->container['service.registration']->get($registrationID);
		
		$password = NULL;
		$this->container['service.login']->createEventAdmin(
				$eventID,
				$registration['TwitterName'],
				$password
		);
		
		$this->container['service.event.teamMember']->save(
				$eventID,
				1,
				$registration['TwitterName']
		);
		
		// TODO: Email notification to $oranizerEmailAddress, with direct link to admin (http://$$subdomain.twestival.com/admin), and generated username/password
		
		
		return $eventID;
	}
}
?>