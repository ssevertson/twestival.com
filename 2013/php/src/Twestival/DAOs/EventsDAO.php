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
				AND Location.Type = UPPER(?)
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
	
	function items($year, $active)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
				SELECT
					Event.*,
					(
						SELECT Location.Name
						FROM EventLocation
						INNER JOIN Location
						ON EventLocation.LocationID = Location.LocationID
						WHERE EventLocation.EventID = Event.EventID
						AND Location.Type = \'CONTINENT\'
					) As LocationContinent,
					(
						SELECT Location.Name
						FROM EventLocation
						INNER JOIN Location
						ON EventLocation.LocationID = Location.LocationID
						WHERE EventLocation.EventID = Event.EventID
						AND Location.Type = \'COUNTRY\'
					) As LocationCountry,
					(
						SELECT Location.Name
						FROM EventLocation
						INNER JOIN Location
						ON EventLocation.LocationID = Location.LocationID
						WHERE EventLocation.EventID = Event.EventID
						AND Location.Type = \'STATE_PROVINCE\'
					) As LocationStateProvince,
					(
						SELECT Location.Name
						FROM EventLocation
						INNER JOIN Location
						ON EventLocation.LocationID = Location.LocationID
						WHERE EventLocation.EventID = Event.EventID
						AND Location.Type = \'CITY\'
					) As LocationCity,
					Blog.Subdomain AS BlogSubdomain
				FROM
					Event
					INNER JOIN Blog
						ON Event.BlogID = Blog.BlogID
				WHERE
					Event.Year = ?
					AND Event.Active = ?
				ORDER BY
					Event.Name;
		');
		$query->bindValue(1, intval($year), \PDO::PARAM_INT);
		$query->bindValue(2, $this->toBoolean($active), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	function get($eventID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
				SELECT
					Event.*,
					(
						SELECT Location.Name
						FROM EventLocation
						INNER JOIN Location
						ON EventLocation.LocationID = Location.LocationID
						WHERE EventLocation.EventID = Event.EventID
						AND Location.Type = \'CONTINENT\'
					) As LocationContinent,
					(
						SELECT Location.Name
						FROM EventLocation
						INNER JOIN Location
						ON EventLocation.LocationID = Location.LocationID
						WHERE EventLocation.EventID = Event.EventID
						AND Location.Type = \'COUNTRY\'
					) As LocationCountry,
					(
						SELECT Location.Name
						FROM EventLocation
						INNER JOIN Location
						ON EventLocation.LocationID = Location.LocationID
						WHERE EventLocation.EventID = Event.EventID
						AND Location.Type = \'STATE_PROVINCE\'
					) As LocationStateProvince,
					(
						SELECT Location.Name
						FROM EventLocation
						INNER JOIN Location
						ON EventLocation.LocationID = Location.LocationID
						WHERE EventLocation.EventID = Event.EventID
						AND Location.Type = \'CITY\'
					) As LocationCity,
				Blog.Subdomain AS BlogSubdomain
				FROM
					Event
					INNER JOIN Blog
						ON Event.BlogID = Blog.BlogID
				WHERE
					Event.EventID = ?
				ORDER BY
					Event.Name;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}
	
	function itemsByLocationName($year, $locationType)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				COALESCE((
						SELECT Location.Name
						FROM EventLocation
						INNER JOIN Location
						ON EventLocation.LocationID = Location.LocationID
						WHERE EventLocation.EventID = Event.EventID
						AND Location.Type = ?
				), \'Unknown\') As LocationName,
				Event.*,
				Blog.Subdomain AS BlogSubdomain
			FROM
				Event
				INNER JOIN Blog
					ON Event.BlogID = Blog.BlogID
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
					ON Registration.Year >= Event.Year
					AND (
						double_metaphone(Registration.City) = (
							SELECT double_metaphone(Location.Name)
							FROM EventLocation
							INNER JOIN Location
							ON EventLocation.LocationID = Location.LocationID
							AND Location.Type = \'CITY\'
							WHERE Event.EventID = EventLocation.EventID
						)
						OR double_metaphone(Registration.City) = (
							SELECT double_metaphone(Blog.Subdomain)
							FROM Blog
							WHERE Event.BlogID = Blog.BlogID
						)
						OR double_metaphone(Registration.EmailAddress) = double_metaphone(Event.OrganizerEmailAddress)
						OR double_metaphone(REPLACE(LOWER(Registration.PreferredTwestivalName), \'twestival\', \'\')) = double_metaphone(REPLACE(LOWER(Event.Name), \'twestival\', \'\'))
					)
			WHERE
				Registration.RegistrationID = ?
			ORDER BY
				Event.Year DESC,
				Event.Name;
		');
		$query->bindValue(1, intval($registrationID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	function create($registrationID, $blogID, $year, $name, $description, $twitterName, $organizerEmailAddress, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO Event (RegistrationID, BlogID, Year, Name, Description, TwitterName, OrganizerEmailAddress, ImageFilename)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?);
		');
		$query->bindValue(1, intval($registrationID), \PDO::PARAM_INT);
		$query->bindValue(2, intval($blogID), \PDO::PARAM_INT);
		$query->bindValue(3, intval($year), \PDO::PARAM_INT);
		$query->bindValue(4, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(5, $this->trimToNull($description), \PDO::PARAM_STR);
		$query->bindValue(6, $this->trimToNull($twitterName), \PDO::PARAM_STR);
		$query->bindValue(7, $this->trimToNull($organizerEmailAddress), \PDO::PARAM_STR);
		$query->bindValue(8, $this->trimToNull($imageFilename), \PDO::PARAM_STR);
	
		$query->execute();
		return $conn->lastInsertId();
	}
	
	function updateEventAdminFields($eventID, $active, $name)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				Event
			SET
				Event.Active = ?,
				Event.Name = ?
			WHERE
				Event.EventID = ?;
		');
		$query->bindValue(1, $this->toBoolean($active), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(3, intval($eventID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->rowCount();
	}
}
?>