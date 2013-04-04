<?php namespace Twestival\DAOs;

class LocationsDAO extends BaseDAO
{
	function get($locationID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				Location.*
			FROM
				Location
			WHERE
				Location.LocationID = ?;
		');
		$query->bindValue(1, intval($locationID), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	function create($locationID, $type, $name, $population, $latitude, $longitude)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO Location(LocationID, Type, Name, Population, Latitude, Longitude)
			VALUES (?, ?, ?, ?, ?, ?);
		');
		$query->bindValue(1, intval($locationID), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($type), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(4, intval($population), \PDO::PARAM_INT);
		$query->bindValue(5, floatval($latitude), \PDO::PARAM_STR); // No PARAM_FLOAT or equivalent
		$query->bindValue(6, floatval($longitude), \PDO::PARAM_STR);
		
		$query->execute();
		return $locationID;
	}
	function updatePopulation($locationID, $population) 
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				Location
			SET
				Location.Population = ?
			WHERE
				Location.LocationID = ?;
		');
		$query->bindValue(1, intval($population), \PDO::PARAM_INT);
		$query->bindValue(2, intval($locationID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->rowCount();
	}

}
?>