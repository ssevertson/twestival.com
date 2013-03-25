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
		$query->bindValue(2, intval($year), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP);
	}
	
	function findPriorRelatedToRegistration($registrationID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				Event.*
			FROM
				Registration
				INNER JOIN Event
					ON Registration.Year > Event.Year
					AND (
						double_metaphone(Registration.City) = (
							SELECT double_metaphone(Location.Name)
							FROM EventLocation
							INNER JOIN Location
							ON EventLocation.LocationID = Location.LocationID
							AND Location.Type = \'CITY\'
							WHERE Event.EventID = EventLocation.EventID
						)
						OR double_metaphone(Registration.EmailAddress) = double_metaphone(Event.OrganizerEmailAddress)
						OR double_metaphone(REPLACE(LOWER(Registration.PreferredTwestivalName), \'twestival\', \'\')) = double_metaphone(REPLACE(LOWER(Event.Name), \'twestival\', \'\'))
					)
			WHERE
				Registration.RegistrationID = ?
				AND Event.Active = TRUE
			ORDER BY
				Event.Name;
		');
		$query->bindValue(1, intval($registrationID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
}
?>