<?php namespace Twestival\DAOs;

class BlogsDAO extends BaseDAO
{
	function items($year)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
				SELECT
					Blog.*
				FROM
					Event
					INNER JOIN Blog
						ON Event.BlogID = Blog.BlogID
				WHERE
					Event.Active = TRUE
					AND Blog.Active = TRUE
					AND Event.Year = ?
				ORDER BY
					Blog.Subdomain;
		');
		$query->bindValue(1, intval($year), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	function getBySubdomain($subdomain)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
				SELECT
					Blog.*
				FROM
					Event
					INNER JOIN Blog
						ON Event.BlogID = Blog.BlogID
				WHERE
					Event.Active = TRUE
					AND Blog.Active = TRUE
					AND Blog.Subdomain = ?;
		');
		$query->bindValue(1, $subdomain, \PDO::PARAM_STR);
		
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}
	function getByID($blogID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
				SELECT
					Blog.*
				FROM
					Event
					INNER JOIN Blog
						ON Event.BlogID = Blog.BlogID
				WHERE
					Event.Active = TRUE
					AND Blog.Active = TRUE
					AND Blog.BlogID = ?;
		');
		$query->bindValue(1, intval($blogID), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}
}
?>