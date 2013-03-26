<?php namespace Twestival\DAOs;

class BlogsDAO extends BaseDAO
{
	function items()
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
				SELECT DISTINCT
					Blog.*,
					Event.EventID,
					EXISTS (
						SELECT 1
						FROM Event
						INNER JOIN Year
							ON Event.Year = Year.Year
						WHERE
							Blog.BlogID = Event.BlogID
							AND Event.Active = TRUE
							AND Year.Active = TRUE
					) AS EventYearActive
				FROM
					Blog
					INNER JOIN Event
						ON Blog.BlogID = Event.BlogID
				WHERE
					Blog.Active = TRUE
					AND Event.Active = TRUE
				ORDER BY
					Blog.Subdomain;
		');
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	function get($subdomain)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
				SELECT DISTINCT
					Blog.*,
					Event.EventID,
					EXISTS (
						SELECT 1
						FROM Event
						INNER JOIN Year
							ON Event.Year = Year.Year
						WHERE
							Blog.BlogID = Event.BlogID
							AND Event.Active = TRUE
							AND Year.Active = TRUE
					) AS EventYearActive
				FROM
					Blog
					INNER JOIN Event
						ON Blog.BlogID = Event.BlogID
				WHERE
					Blog.Active = TRUE
					AND Event.Active = TRUE
					AND Blog.Subdomain = ?;
		');
		$query->bindValue(1, $subdomain, \PDO::PARAM_STR);
		
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}
	
	function findUnassignedForActiveYear()
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
				SELECT DISTINCT
					Blog.*
				FROM
					Blog
				WHERE
					Blog.Active = TRUE
					AND NOT EXISTS (
						SELECT 1
						FROM Event
						INNER JOIN Year
							ON Event.Year = Year.Year
						WHERE
							Blog.BlogID = Event.BlogID
							AND Event.Active = TRUE
							AND Year.Active = TRUE
					)
				ORDER BY
					Blog.Subdomain;
		');
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	function create($subdomain)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO Blog (Subdomain)
			VALUES (?);
		');
		$query->bindValue(1, $this->trimToNull($subdomain), \PDO::PARAM_STR);
		
		$query->execute();
		return $conn->lastInsertId();
	}
}
?>