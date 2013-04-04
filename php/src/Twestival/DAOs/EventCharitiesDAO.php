<?php namespace Twestival\DAOs;

class EventCharitiesDAO extends BaseDAO
{
	function items($eventID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventCharity.*
			FROM
				Event
				INNER JOIN EventCharity
					ON Event.EventID = EventCharity.EventID
			WHERE
				Event.Active = TRUE
				AND EventCharity.EventID = ?
			ORDER BY
				EventCharity.Sequence;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	function countRunningEventCharities($baselineYear)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				COUNT(*)
			FROM
				Event
				INNER JOIN EventCharity
					ON Event.EventID = EventCharity.EventID
			WHERE
				Event.Year >= ?;
		');
		$query->bindValue(1, intval($baselineYear), \PDO::PARAM_INT);
		
		$query->execute();
		return intval($query->fetchColumn());
	}
	function update($eventID, $eventCharityID, $name, $uri, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				EventCharity
			SET
				EventCharity.Name = ?,
				EventCharity.Uri = ?,
				EventCharity.ImageFilename = ?
			WHERE
				EventCharity.EventID = ?
				AND EventCharity.CharityID = ?;
		');
		$query->bindValue(1, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(2, $this->trimToNull($uri), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($imageFilename), \PDO::PARAM_STR);
		$query->bindValue(4, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(5, intval($eventCharityID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->rowCount();
	}
	function create($eventID, $name, $uri, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO EventCharity(EventID, Sequence, Name, Uri, ImageFilename)
			SELECT
				?,
				MAX(EventCharity.Sequence) + 1,
				?,
				?,
				?
			FROM
				EventCharity
			WHERE
				EventCharity.EventID = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($uri), \PDO::PARAM_STR);
		$query->bindValue(4, $this->trimToNull($imageFilename), \PDO::PARAM_STR);
		$query->bindValue(5, intval($eventID), \PDO::PARAM_INT);
		
		$query->execute();
		return $conn->lastInsertId();
	}
}
?>