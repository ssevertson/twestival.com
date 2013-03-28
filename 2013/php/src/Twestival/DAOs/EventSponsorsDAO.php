<?php namespace Twestival\DAOs;

class EventSponsorsDAO extends BaseDAO
{
	function items($eventID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventSponsor.*,
				Event.Year <= 2011 AS Legacy
			FROM
				Event
				INNER JOIN EventSponsor
					ON Event.EventID = EventSponsor.EventID
			WHERE
				Event.EventID = ?
			ORDER BY
				EventSponsor.Sequence;
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
				EventSponsor
			WHERE
				EventSponsor.EventID = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
	
		$query->execute();
		return intval($query->fetchColumn());
	}
	function get($eventID, $eventSponsorID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventSponsor.*,
				Event.Year <= 2011 AS Legacy
			FROM
				Event
				INNER JOIN EventSponsor
					ON Event.EventID = EventSponsor.EventID
			WHERE
				Event.EventID = ?
				AND EventSponsor.SponsorID = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, intval($eventSponsorID), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}
	
	function create($eventID, $name, $uri, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
				INSERT INTO EventSponsor(EventID, Sequence, Name, Uri, ImageFilename)
				SELECT 
					?,
					MAX(EventSponsor.Sequence) + 1,
					?,
					?,
					?
				FROM
					EventSponsor
				WHERE
					EventSponsor.EventID = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($uri), \PDO::PARAM_STR);
		$query->bindValue(4, $this->trimToNull($imageFilename), \PDO::PARAM_STR);
		$query->bindValue(5, intval($eventID), \PDO::PARAM_INT);
		
		$query->execute();
		return $conn->lastInsertId();
	}
	
	function update($eventID, $eventSponsorID, $name, $uri, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				EventSponsor
			SET
				EventSponsor.Name = ?,
				EventSponsor.Uri = ?,
				EventSponsor.ImageFilename = ?
			WHERE
				EventSponsor.EventID = ?
				AND EventSponsor.SponsorID = ?;
		');
		$query->bindValue(1, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(2, $this->trimToNull($uri), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($imageFilename), \PDO::PARAM_STR);
		$query->bindValue(4, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(5, intval($eventSponsorID), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->rowCount();
	}
	function updateSequence($eventID, $sequence, $newSequence)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				EventSponsor
			SET
				EventSponsor.Sequence = ?
			WHERE
				EventSponsor.EventID = ?
				AND EventSponsor.Sequence = ?;
		');
		$query->bindValue(1, intval($newSequence), \PDO::PARAM_INT);
		$query->bindValue(2, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(3, intval($sequence), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->rowCount();
	}
	function delete($eventID, $eventSponsorID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			DELETE FROM
				EventSponsor
			WHERE
				EventSponsor.EventID = ?
				AND EventSponsor.SponsorID = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, intval($eventSponsorID), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->rowCount();
	}
}
?>