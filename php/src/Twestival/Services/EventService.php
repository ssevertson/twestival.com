<?php namespace Twestival\Services;

class EventService extends BaseService
{
	const BASE_URI_TWITTER = 'https://twitter.com/';
	const DEFAULT_IMAGE_FILENAME = 'event-default.png';
	const IMAGE_FILENAME_PREFIX = 'event-';
	
	function getByContinent($year)
	{
		$grouped = $this->container['dao.events']->itemsByLocationName($year, 'CONTINENT');
		foreach($grouped as $continent => &$events)
		{
			$this->addUrisToEvents($events);
		}
		return $grouped;
	}
	function getEvent($eventId)
	{
		$event = $this->container['dao.events']->get($eventId);
		$this->addUrisToEvent($event);
		return $event;
	}

	function getEvents($year, $active)
	{
		$events = $this->container['dao.events']->items($year, $active);
		$this->addUrisToEvents($events);
		return $events;
	}
	function findPriorRelatedToRegistration($registrationID)
	{
		$events = $this->container['dao.events']->findPriorRelatedToRegistration($registrationID);
		return $events;
	}
	function search($year, $q)
	{
		$events = $this->container['dao.events']->search($year, $q);
		$this->addUrisToEvents($events);
		return $events;
	}
	
	private function addUrisToEvents(&$events)
	{
		foreach($events as &$event)
		{
			$this->addUrisToEvent($event);
		}
	}
	private function addUrisToEvent(&$event)
	{
		$event['TwitterUri'] = EventService::BASE_URI_TWITTER . $event['TwitterName'];
		if(isset($event['BlogSubdomain']))
		{
			$event['BlogUri'] = 'http://' 
					. $event['BlogSubdomain']
					. '.' 
					. $this->container['request.domain']
					. $this->container['baseUri'];
		}
		if(isset($event['ImageFilename']))
		{
			$event['ImageUri'] = $this->getImageUri($event['ImageFilename']);
		}
	}
	function getImagePath()
	{
		return 'img/event/content';
	}
	function getImageUri($imageFilename = '')
	{
		return $this->container['request.protocol']
		. $this->container['request.hostname']
		. $this->container['baseUri']
		. '/'
		. $this->getImagePath()
		. '/'
		. $imageFilename;
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
	
		$blogSubdomain = preg_replace('/\s/', '', strtolower($registration['City']));
	
		return array(
				'Name' => $name,
				'Description' => 'Uses social media for social good by connecting communities to highlight a greater cause and have a fun event.',
				'TwitterName' => $registration['TwitterName'],
				'OrganizerEmailAddress' => $registration['EmailAddress'],
				'BlogID' => $relatedBlogID,
				'BlogSubdomain' => $blogSubdomain
		);
	}
	
	function create($registrationID, $blogID, $currentYear, $name, $description, $twitterName, $organizerEmailAddress, $locationID)
	{
		$eventID = $this->container['dao.events']->create(
				$registrationID,
				$blogID,
				$currentYear,
				$name,
				$description,
				$twitterName,
				$organizerEmailAddress,
				EventService::DEFAULT_IMAGE_FILENAME);
		
		$blog = $this->container['service.blog']->getByID($blogID);

		$registration = $this->container['service.registration']->get($registrationID);
		
		$password = NULL;
		$this->container['service.login']->createEventAdmin(
				$eventID,
				$registration['TwitterName'],
				$password
		);
		
		$this->container['service.event.teamMember']->create(
				$eventID,
				$registration['TwitterName']
		);
		
		$this->container['service.location']->saveEventLocationCity(
				$eventID,
				$locationID);
		
		$this->container['email.mailer']->send(
				$this->container['email.message']
				->setSubject('Welcome to Twestival ' . $currentYear . '!')
				->setBody(
						$this->container['mustache.engine']->loadTemplate('Email/EventApproval')->render(array(
								'Username' => $registration['TwitterName'],
								'Password' => $password,
								'CurrentYear' => $currentYear,
								'BlogUri' => 'https://' 
										. $blog['Subdomain']
										. '.' 
										. $this->container['request.domain']
										. $this->container['baseUri'],
								'GlobalUri' => 'https://'
										. $this->container['request.subdomain.global']
										. '.'
										. $this->container['request.domain']
										. $this->container['baseUri']
						))
						, 'text/html')
				->setTo(array(
						$organizerEmailAddress => $name
				))
		);
		
		
		return $eventID;
	}
	
	function saveEventAdminFields($eventID, $imageFilename, $fundraisingGoalUsd, $donationTotalUSD, $attendUri, $donateUri, $description, $date, $startTime, $endTime,
			$locationName, $locationAddress1, $locationAddress2, $locationUri, $organizerEmailAddress, $twitterName, $facebookUri, $twitterShareMessage)
	{
		return $this->container['dao.events']->updateEventAdminFields(
				$eventID, $imageFilename, $fundraisingGoalUsd, $donationTotalUSD, $attendUri, $donateUri, $description, $date, $startTime, $endTime,
				$locationName, $locationAddress1, $locationAddress2, $locationUri, $organizerEmailAddress, $twitterName, $facebookUri, $twitterShareMessage);
	}
	
	function saveSiteAdminFields($eventID, $active, $name)
	{
		$this->container['dao.events']->updateSiteAdminFields(
				$eventID,
				$active,
				$name
		);
	}
}
?>