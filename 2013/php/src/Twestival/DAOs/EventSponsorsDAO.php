<?php namespace Twestival\DAOs;

class EventSponsorsDAO extends BaseDAO
{
	function items($eventID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventSponsor.*
			FROM
				EventSponsor
			WHERE
				EventSponsor.EventID = ?
			ORDER BY
				EventSponsor.Sequence;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	function get($eventID, $sequence)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventSponsor.*
			FROM
				EventSponsor
			WHERE
				EventSponsor.EventID = ?
				AND EventTeamMember.Sequence = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, intval($sequence), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	
	function create($eventID, $sequence, $name, $uri, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO EventSponsor(EventID, Sequence, Name, URL, ImageFilename)
			VALUES (?, ?, ?, ?, ?);
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, intval($sequence), \PDO::PARAM_INT);
		$query->bindValue(3, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(4, $this->trimToNull($uri), \PDO::PARAM_STR);
		$query->bindValue(5, $this->trimToNull($imageFilename), \PDO::PARAM_STR);
		
		$query->execute();
		return $conn->lastInsertId();
	}
	
	function update($eventID, $sequence, $name, $url, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				EventSponsor
			SET
				EventSponsor.Name = ?,
				EventSponsor.URL = ?,
				EventSponsor.ImageFilename = ?
			WHERE
				EventTeamMember.EventID = ?
				AND EventTeamMember.Sequence = ?;
		');
		$query->bindValue(1, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(2, $this->trimToNull($uri), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($imageFilename), \PDO::PARAM_STR);
		$query->bindValue(4, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(5, intval($sequence), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->rowCount();
	}
}
?>