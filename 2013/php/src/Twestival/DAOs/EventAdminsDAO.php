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
				EventAdmin.Username = ?
				AND EventAdmin.Password = UPPER(SHA1(CONCAT(?, EventAdmin.Salt)))
				AND Blog.Subdomain = ?
				AND Year.Active = TRUE;
		');
		$query->bindValue(1, $username, \PDO::PARAM_STR);
		$query->bindValue(1, $password, \PDO::PARAM_STR);
		$query->bindValue(1, $blogSubdomain, \PDO::PARAM_STR);
		$query->execute();
		return intval($query->fetchColumn());
	}
}
?>