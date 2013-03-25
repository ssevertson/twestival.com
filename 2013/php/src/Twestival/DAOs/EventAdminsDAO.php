<?php namespace Twestival\DAOs;

class EventAdminsDAO extends BaseDAO
{
	function authenticate($username, $password, $blogSubdomain)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventAdmin.EventAdminID
			FROM
				EventAdmin
				INNER JOIN Event
					ON EventAdmin.EventID = Event.EventID
				INNER JOIN Blog
					ON Event.BlogID = Blog.BlogID
				INNER JOIN Year
					ON Event.Year = Year.Year
			WHERE
				Blog.Active = TRUE
				AND Year.Active = TRUE
				AND EventAdmin.Username = ?
				AND EventAdmin.Password = UPPER(SHA1(CONCAT(?, EventAdmin.Salt)))
				AND Blog.Subdomain = ?;
		');
		$query->bindValue(1, $username, \PDO::PARAM_STR);
		$query->bindValue(2, $password, \PDO::PARAM_STR);
		$query->bindValue(3, $blogSubdomain, \PDO::PARAM_STR);
		$query->execute();
		return intval($query->fetchColumn());
	}
	
	function create($eventID, $username, $password, $salt)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO EventAdmin(EventID, Username, Password, Salt)
			SELECT
				?,
				?,
				UPPER(SHA1(CONCAT(?, ?))),
				?;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($username), \PDO::PARAM_STR);
		$query->bindValue(3, $password, \PDO::PARAM_STR);
		$query->bindValue(4, $salt, \PDO::PARAM_STR);
		$query->bindValue(5, $salt, \PDO::PARAM_STR);
		$query->execute();
		return $conn->lastInsertId();
	}
}
?>