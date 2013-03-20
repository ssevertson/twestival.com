<?php namespace Twestival\DAOs;

class EventsDAO extends BaseDAO
{
	function countEventLocationsByType($locationType) {
		$db = $this->container['db_ro'];
		$query = $db->prepare('
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
	
	function sumEventDonationTotalUSD() {
		$db = $this->container['db_ro'];
		$query = $db->prepare('
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
}
?>