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
	
	function create($locationID, $iso3166Code, $type, $name, $population, $latitude, $longitude)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO Location(LocationID, ISO3166Code, Type, Name, Population, Latitude, Longitude)
			VALUES (?, ?, ?, ?, ?, ?, ?);
		');
		$query->bindValue(1, intval($locationID), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($iso3166Code), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($type), \PDO::PARAM_STR);
		$query->bindValue(4, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(5, intval($population), \PDO::PARAM_INT);
		$query->bindValue(6, floatval($latitude), \PDO::PARAM_STR); // No PARAM_FLOAT or equivalent
		$query->bindValue(7, floatval($longitude), \PDO::PARAM_STR);
		
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
	function updateISO3166Code($locationID, $iso3166Code)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				Location
			SET
				Location.ISO3166Code = ?
			WHERE
				Location.LocationID = ?;
		');
		$query->bindValue(1, $this->trimToNull($iso3166Code), \PDO::PARAM_STR);
		$query->bindValue(2, intval($locationID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->rowCount();
	}
	function getLocationTotalsByType($year, $locationType)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				Location.LocationID,
				Location.ISO3166Code,
				Location.Type,
				Location.Name,
				Location.Population,
				Location.Latitude,
				Location.Longitude,
				COALESCE(SUM(Event.DonationTotalUSD), 0) AS DonationTotalUSD
			FROM
				Event
				INNER JOIN EventLocation
					ON Event.EventID = EventLocation.EventID
				INNER JOIN Location
					ON EventLocation.LocationID = Location.LocationID
			WHERE
				Event.Active = TRUE
				AND Event.Year = ?
				AND Location.Type = ?
			GROUP BY
				Location.LocationID,
				Location.ISO3166Code,
				Location.Type,
				Location.Name,
				Location.Population,
				Location.Latitude,
				Location.Longitude
			ORDER BY
				Location.Name
		');
		$query->bindValue(1, intval($year), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($locationType), \PDO::PARAM_STR);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	function getLocationTotalsByTypeAndID($year, $locationType, $locationID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				Location.LocationID,
				Location.ISO3166Code,
				Location.Type,
				Location.Name,
				Location.Population,
				Location.Latitude,
				Location.Longitude,
				COALESCE(SUM(Event.DonationTotalUSD), 0) AS DonationTotalUSD
			FROM
				Event
				INNER JOIN EventLocation
					ON Event.EventID = EventLocation.EventID
				INNER JOIN Location
					ON EventLocation.LocationID = Location.LocationID
			WHERE
				Event.Active = TRUE
				AND Event.Year = ?
				AND Location.Type = ?
				AND Event.EventID IN (
					SELECT EventLocation.EventID
					FROM EventLocation
					WHERE EventLocation.LocationID = ?
				)
			GROUP BY
				Location.LocationID,
				Location.ISO3166Code,
				Location.Type,
				Location.Name,
				Location.Population,
				Location.Latitude,
				Location.Longitude
			ORDER BY
				Location.Name
		');
		$query->bindValue(1, intval($year), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($locationType), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($locationID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

}
?>