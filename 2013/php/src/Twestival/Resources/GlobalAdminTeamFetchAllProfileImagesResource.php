<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /admin/event/team/fetch-all-profile-images
 */
class GlobalAdminTeamFetchAllProfileImagesResource extends BaseBlogResource
{
	/**
	 * @method post
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function fetchAll()
	{
		/*
		 * This is a hack implementation to quickly grab all the Twitter Profile images for event team members.
		 * It really should be implemented using the standard framework, but it works for a one-off.
		 */
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT DISTINCT
				EventTeamMember.TwitterName
			FROM
				EventTeamMember;
		');
		$query->execute();
		$teamMembers = $query->fetchAll(\PDO::FETCH_ASSOC);
		
		$twitterNames = array();
		foreach($teamMembers as $teamMember)
		{
			array_push($twitterNames, $teamMember['TwitterName']);
		}
		
		$limit = 50;
		$client = $this->container['twitter.twestival.client'];
		
		$output = '';
		
		for($i = 0; $i < count($twitterNames); $i+= $limit)
		{
			$batch = array_slice($twitterNames, $i, $limit);
			
			$output .= 'Batch: ' . implode(',', $batch) . "\n";
			
			$request = $client->get('users/lookup.json');
			$request->getQuery()->merge(array(
					'screen_name' => implode(',', $batch)
			));
			
			$response = $request->send();
			$data = $response->json();
			
			foreach($data as $result)
			{
				$imageUri = $result['profile_image_url'];
				$twitterName = $result['screen_name'];
				$lastSlashPos = strrpos($imageUri, '/');
				$extensonPos = strrpos($imageUri, '.', $lastSlashPos);
				$extension = $extensonPos ? substr($imageUri, $extensonPos + 1) : '.jpg';
				$imageFilename = uniqid('team-') . '.' . $extension;
				$imagePath = $this->container['baseDir'] . '/../img/event/content/' . $imageFilename;
				$output .= $imageFilename . "\n";
				
				file_put_contents($imagePath, file_get_contents($imageUri));
				
				$query = $conn->prepare('
						UPDATE
							EventTeamMember
						SET
							EventTeamMember.ImageFilename = ?
						WHERE
							EventTeamMember.TwitterName = ?;
				');
				$query->bindValue(1, $imageFilename, \PDO::PARAM_STR);
				$query->bindValue(2, $twitterName, \PDO::PARAM_STR);
				$query->execute();
			}
		}
		$output .= "Done\n";
		return $output;
	}
}
?>