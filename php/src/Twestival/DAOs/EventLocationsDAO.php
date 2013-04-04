<?php namespace Twestival\DAOs;

class EventLocationsDAO extends BaseDAO
{
	function create($eventID, $locationID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO EventLocation(EventID, LocationID)
			VALUES (?, ?);
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, intval($locationID), \PDO::PARAM_INT);
		
		$query->execute();
		return 1;
	}
	function delete($eventID) 
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			DELETE FROM
				EventLocation
			WHERE
				EventLocation.EventID = ?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->rowCount();
	}

}
?>