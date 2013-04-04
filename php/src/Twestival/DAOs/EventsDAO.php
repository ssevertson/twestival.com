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
				AND Event.Active = TRUE
				AND Location.Type = UPPER(?);
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
				AND Event.Active = TRUE;
		');
		$query->execute();
		return floatval($query->fetchColumn());
	}
	
	function sumRunningEventDonationTotalUSD($baselineYear)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				SUM(Event.DonationTotalUSD)
			FROM
				Event
			WHERE
				Event.Year >= ?;
		');
		
		$query->bindValue(1, intval($baselineYear), \PDO::PARAM_INT);
		
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
					Location.Name AS LocationCity,
					Location.Latitude AS LocationLatitude,
					Location.Longitude AS LocationLongitude,
					Blog.Subdomain AS BlogSubdomain
				FROM
					Event
					INNER JOIN Blog
						ON Event.BlogID = Blog.BlogID
					LEFT JOIN (
						EventLocation
						INNER JOIN Location
							ON EventLocation.LocationID = Location.LocationID
							AND Location.Type = \'CITY\'
					)
						ON Event.EventID = EventLocation.EventID
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
					Location.Name AS LocationCity,
					Location.Latitude AS LocationLatitude,
					Location.Longitude AS LocationLongitude,
					Blog.Subdomain AS BlogSubdomain
				FROM
					Event
					INNER JOIN Blog
						ON Event.BlogID = Blog.BlogID
					LEFT JOIN (
						EventLocation
						INNER JOIN Location
							ON EventLocation.LocationID = Location.LocationID
							AND Location.Type = \'CITY\'
					)
						ON Event.EventID = EventLocation.EventID
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
					Location.Name AS LocationCity,
					Location.Latitude AS LocationLatitude,
					Location.Longitude AS LocationLongitude,
					Blog.Subdomain AS BlogSubdomain
				FROM
					Event
					INNER JOIN Blog
						ON Event.BlogID = Blog.BlogID
					LEFT JOIN (
						EventLocation
						INNER JOIN Location
							ON EventLocation.LocationID = Location.LocationID
							AND Location.Type = \'CITY\'
					)
						ON Event.EventID = EventLocation.EventID
				WHERE
					Event.Year = ?
					AND Event.Active = TRUE
				ORDER BY
					LocationName,
					Event.Name;
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
	function search($year, $q)
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
					Location.Name AS LocationCity,
					Location.Latitude AS LocationLatitude,
					Location.Longitude AS LocationLongitude,
					Blog.Subdomain AS BlogSubdomain
				FROM
					Event
					INNER JOIN Blog
						ON Event.BlogID = Blog.BlogID
					LEFT JOIN (
						EventLocation
						INNER JOIN Location
							ON EventLocation.LocationID = Location.LocationID
							AND Location.Type = \'CITY\'
					)
						ON Event.EventID = EventLocation.EventID
				WHERE
					Event.Year = ?
					AND (
						Event.Name LIKE CONCAT(\'%\', ?, \'%\')
						OR EXISTS (
							SELECT 1
							FROM EventLocation
							INNER JOIN Location
								ON EventLocation.LocationID = Location.LocationID
							WHERE Event.EventID = EventLocation.EventID
							AND double_metaphone(Location.Name) LIKE CONCAT(double_metaphone(?), \'%\')
						)
					)
				ORDER BY
					Event.Year DESC,
					Event.Name;
		');
		$query->bindValue(1, intval($year), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($q), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($q), \PDO::PARAM_STR);
		
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
	
	function updateSiteAdminFields($eventID, $active, $name)
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
	
	function updateEventAdminFields($eventID, $imageFilename, $fundraisingGoalUsd, $donationTotalUSD, $attendUri, $donateUri, $description, $date, $startTime, $endTime,
			$locationName, $locationAddress1, $locationAddress2, $locationUri, $organizerEmailAddress, $twitterName, $facebookUri, $twitterShareMessage)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				Event
			SET
				Event.ImageFilename = ?,
				Event.FundraisingGoalUSD = ?,
				Event.DonationTotalUSD = ?,
				Event.AttendUri = ?,
				Event.DonateUri = ?,
				Event.Description = ?,
				Event.Date = ?,
				Event.StartTime = ?,
				Event.EndTime = ?,
				Event.LocationName = ?,
				Event.LocationAddress1 = ?,
				Event.LocationAddress2 = ?,
				Event.LocationUri = ?,
				Event.OrganizerEmailAddress = ?,
				Event.TwitterName = ?,
				Event.FacebookUri = ?,
				Event.TwitterShareMessage = ?
			WHERE
				Event.EventID = ?;
		');
		$query->bindValue(1, $this->trimToNull($imageFilename), \PDO::PARAM_STR);
		$query->bindValue(2, intval($fundraisingGoalUsd), \PDO::PARAM_INT);
		$query->bindValue(3, intval($donationTotalUSD), \PDO::PARAM_INT);
		$query->bindValue(4, $this->trimToNull($attendUri), \PDO::PARAM_STR);
		$query->bindValue(5, $this->trimToNull($donateUri), \PDO::PARAM_STR);
		$query->bindValue(6, $this->trimToNull($description), \PDO::PARAM_STR);
		$query->bindValue(7, $this->toDate($date), \PDO::PARAM_STR);
		$query->bindValue(8, $this->trimToNull($startTime), \PDO::PARAM_STR);
		$query->bindValue(9, $this->trimToNull($endTime), \PDO::PARAM_STR);
		$query->bindValue(10, $this->trimToNull($locationName), \PDO::PARAM_STR);
		$query->bindValue(11, $this->trimToNull($locationAddress1), \PDO::PARAM_STR);
		$query->bindValue(12, $this->trimToNull($locationAddress2), \PDO::PARAM_STR);
		$query->bindValue(13, $this->trimToNull($locationUri), \PDO::PARAM_STR);
		$query->bindValue(14, $this->trimToNull($organizerEmailAddress), \PDO::PARAM_STR);
		$query->bindValue(15, $this->trimToNull($twitterName), \PDO::PARAM_STR);
		$query->bindValue(16, $this->trimToNull($facebookUri), \PDO::PARAM_STR);
		$query->bindValue(17, $this->trimToNull($twitterShareMessage), \PDO::PARAM_STR);
		$query->bindValue(18, intval($eventID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->rowCount();
		
	}
}
?>