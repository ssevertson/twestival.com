<?php namespace Twestival\DAOs;

class EventsDAO extends BaseDAO
{
	function countEventLocationsByType($locationType)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				COUNT(DISTINCT Location.LocationID)
			FROM
				Year
				INNER JOIN Event
					ON Year.Year = Event.Year
				INNER JOIN EventLocation
					ON Event.EventId = EventLocation.EventId
				INNER JOIN Location
					ON EventLocation.LocationID = Location.LocationID
			WHERE
				Year.Active = TRUE
				AND Location.Type = ?
		');
		$query->bindValue(1, $locationType, \PDO::PARAM_STR);
		$query->execute();
		return intval($query->fetchColumn());
	}
	
	function sumEventDonationTotalUSD()
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				SUM(Event.DonationTotalUSD)
			FROM
				Year
				INNER JOIN Event
					ON Year.Year = Event.Year
			WHERE
				Year.Active = TRUE
		');
		$query->execute();
		return floatval($query->fetchColumn());
	}
	
	
	function itemsByLocationName($year, $locationType)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				COALESCE((
					SELECT
						Location.Name
					FROM
						EventLocation
						INNER JOIN Location
							ON EventLocation.LocationID = Location.LocationID
					WHERE
						EventLocation.EventID = Event.EventID
						AND Location.Type = ?
				), \'Unknown\') As LocationName,
				Event.*
			FROM
				Event
			WHERE
				Event.Year = ?
				AND Event.Active = TRUE
			ORDER BY
				LocationName,
				Event.Name
		');
		$query->bindValue(1, $locationType, \PDO::PARAM_STR);
		$query->bindValue(2, $year, \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP);
	}
}
?>