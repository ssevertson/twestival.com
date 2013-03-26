<?php namespace Twestival\DAOs;

class EventTeamMembersDAO extends BaseDAO
{
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
	function get($eventID, $sequence)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventTeamMember.*
			FROM
				EventTeamMember
			WHERE
				EventTeamMember.EventID = ?
				AND EventTeamMember.Sequence = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, intval($sequence), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	
	function create($eventID, $sequence, $twitterName)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO EventTeamMember(EventID, Sequence, TwitterName)
			VALUES (?, ?, ?);
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, intval($sequence), \PDO::PARAM_INT);
		$query->bindValue(3, $this->trimToNull($twitterName), \PDO::PARAM_STR);
		
		$query->execute();
		return $conn->lastInsertId();
	}

	function update($eventID, $sequence, $twitterName)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				EventTeamMember
			SET
				EventTeamMember.TwitterName = ?
			WHERE
				EventTeamMember.EventID = ?
				AND EventTeamMember.Sequence = ?;
		');
		$query->bindValue(1, $this->trimToNull($twitterName), \PDO::PARAM_STR);
		$query->bindValue(2, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(3, intval($sequence), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->rowCount();
	}
}
?>