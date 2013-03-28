<?php namespace Twestival\DAOs;

class EventTeamMembersDAO extends BaseDAO
{
	function get($eventID, $eventTeamMemberID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventTeamMember.*
			FROM
				EventTeamMember
			WHERE
				EventTeamMember.EventID = ?
				AND EventTeamMember.EventTeamMemberID = ?
			ORDER BY
				EventTeamMember.Sequence;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, intval($eventTeamMemberID), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}
	function items($eventID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventTeamMember.*
			FROM
				EventTeamMember
			WHERE
				EventTeamMember.EventID = ?
			ORDER BY
				EventTeamMember.Sequence;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	function count($eventID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				COUNT(*)
			FROM
				EventTeamMember
			WHERE
				EventTeamMember.EventID = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
	
		$query->execute();
		return intval($query->fetchColumn());
	}
	
	
	function create($eventID, $sequence, $twitterName, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO EventTeamMember(EventID, Sequence, TwitterName, ImageFilename)
			SELECT 
				?,
				MAX(EventTeamMember.Sequence) + 1,
				?,
				?
			FROM
				EventTeamMember
			WHERE
				EventTeamMember.EventID = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($twitterName), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($imageFilename), \PDO::PARAM_STR);
		$query->bindValue(4, intval($eventID), \PDO::PARAM_INT);
		
		$query->execute();
		return $conn->lastInsertId();
	}

	function update($eventID, $eventTeamMemberID, $twitterName, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				EventTeamMember
			SET
				EventTeamMember.TwitterName = ?,
				EventTeamMember.ImageFilename = ?
			WHERE
				EventTeamMember.EventID = ?
				AND EventTeamMember.EventTeamMemberID = ?;
		');
		$query->bindValue(1, $this->trimToNull($twitterName), \PDO::PARAM_STR);
		$query->bindValue(2, $this->trimToNull($imageFilename), \PDO::PARAM_STR);
		$query->bindValue(3, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(4, intval($eventTeamMemberID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->rowCount();
	}

	function updateSequence($eventID, $sequence, $newSequence)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				EventTeamMember
			SET
				EventTeamMember.Sequence = ?
			WHERE
				EventTeamMember.EventID = ?
				AND EventTeamMember.Sequence = ?;
		');
		$query->bindValue(1, intval($newSequence), \PDO::PARAM_INT);
		$query->bindValue(2, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(3, intval($sequence), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->rowCount();
	}

	function delete($eventID, $eventTeamMemberID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			DELETE FROM
				EventTeamMember
			WHERE
				EventTeamMember.EventID = ?
				AND EventTeamMember.EventTeamMemberID = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, intval($eventTeamMemberID), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->rowCount();
	}
}
?>