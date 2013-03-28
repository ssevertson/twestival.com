<?php namespace Twestival\Services;

class EventTeamMemberService extends BaseService
{
	const MAX_SEQUENCE = 255;
	const IMAGE_FILENAME_PREFIX = 'team-';
	const DEFAULT_IMAGE_FILENAME = 'team-default.png';
	const DEFAULT_IMAGE_EXTENSION = 'jpg';
	
	function getTeamMember($eventID, $eventTeamMemberID)
	{
		$teamMember = $this->container['dao.event.teamMembers']->get($eventID, $eventTeamMemberID);
		$this->addUrisToTeamMember($teamMember);
		return $teamMember;
	}
	function getTeamMembers($eventID)
	{
		$teamMembers = $this->container['dao.event.teamMembers']->items($eventID);
		$this->addUrisToTeamMembers($teamMembers);
		return $teamMembers;
	}
	function countTeamMembers($eventID)
	{
		return $this->container['dao.event.teamMembers']->count($eventID);
	}
	
	function update($eventID, $eventTeamMemberID, $twitterName)
	{
		$imageFilename = $this->fetchTwitterImage($eventID, $twitterName);
		return $this->container['dao.event.teamMembers']->update($eventID, $eventTeamMemberID, $twitterName, $imageFilename);
	}
	function create($eventID, $twitterName)
	{
		$imageFilename = $this->fetchTwitterImage($eventID, $twitterName);
		return $this->container['dao.event.teamMembers']->create($eventID, $twitterName, $imageFilename);
	}
	
	function updateTwitterImage($eventID, $eventTeamMemberID)
	{
		$teamMember = $this->getTeamMember($eventID, $eventTeamMemberID);
		$twitterName = $teamMember['TwitterName'];
		$imageFilename = $this->fetchTwitterImage($eventID, $twitterName);
		return $this->update($eventID, $eventTeamMemberID, $twitterName, $imageFilename);
	}
	private function fetchTwitterImage($eventID, $twitterName)
	{
		$teamMember = $this->getTeamMember($eventID, $eventTeamMemberID);

		$imageFilename = EventTeamMemberService::DEFAULT_IMAGE_FILENAME;
		
		$client = $this->container['twitter.twestival.client'];
		$request = $client->get('users/show.json');
		$request->getQuery()->merge(array(
				'screen_name' => $twitterName
		));
		try
		{
			$response = $request->send();
			$data = $response->json();
			$imageUri = $data['profile_image_url'];
			
			$lastSlashPos = strrpos($imageUri, '/');
			$extensonPos = strrpos($imageUri, '.', $lastSlashPos);
			$extension = $extensonPos ? substr($imageUri, $extensonPos + 1) : EventTeamMemberService::DEFAULT_IMAGE_EXTENSION;
			$imageFilename = uniqid(EventTeamMemberService::IMAGE_FILENAME_PREFIX) . '.' . $extension;
			$imagePath = $this->container['baseDir'] . '/../' . $this->getImagePath() . '/' . $imageFilename;
			file_put_contents($imagePath, file_get_contents($imageUri));
			
			return $imageFilename;
		}
		catch(Guzzle\Http\Exception\RequestException $ignore) {
			return EventTeamMemberService::DEFAULT_IMAGE_FILENAME;
		}
	}
	
	function moveUp($eventID, $eventTeamMemberID)
	{
		$this->move($eventID, $eventTeamMemberID, -1);
	}
	function moveDown($eventID, $eventTeamMemberID)
	{
		$this->move($eventID, $eventTeamMemberID, 1);
	}
	private function move($eventID, $eventTeamMemberID, $offset)
	{
		$teamMembers = $this->container['dao.event.teamMembers'];
		$teamMember = $this->getTeamMember($eventID, $eventTeamMemberID);
		$sequence = $teamMember['Sequence'];
		
		$teamMembers->updateSequence($eventID, $sequence, EventTeamMemberService::MAX_SEQUENCE);
		$teamMembers->updateSequence($eventID, $sequence + $offset, $sequence);
		$teamMembers->updateSequence($eventID, EventTeamMemberService::MAX_SEQUENCE, $sequence + $offset);
	}
	
	private function addUrisToTeamMembers(&$teamMembers)
	{
		foreach($teamMembers as &$teamMember)
		{
			$this->addUrisToTeamMember($teamMember);
		}
	}
	private function addUrisToTeamMember(&$teamMember)
	{
		$teamMember['ImageUri'] = $this->getImageUri($teamMember['ImageFilename']);
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
	
	function delete($eventID, $eventTeamMemberID)
	{
		$teamMembers = $this->container['dao.event.teamMembers'];
		$teamMember = $teamMembers->get($eventID, $eventTeamMemberID);
		$sequence = $teamMember['Sequence'];
		
		$teamMembers->delete($eventID, $eventTeamMemberID);
	
		foreach ($teamMembers->items($teamMembers) as $teamMember)
		{
			if($teamMember['Sequence'] > $sequence)
			{
				$teamMembers->updateSequence($eventID, $teamMember['Sequence'], $teamMember['Sequence'] - 1);
			}
		}
	}
}
?>