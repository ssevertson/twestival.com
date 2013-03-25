<?php namespace Twestival\DAOs;

class BlogPostsDAO extends BaseDAO
{
	function items($subdomain, $count, $offset)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				BlogPost.*
			FROM
				Blog
				INNER JOIN BlogPost
					ON Blog.BlogID = BlogPost.BlogID
			WHERE
				Blog.ACTIVE = TRUE
				AND Blog.Subdomain = ?
			ORDER BY
				BlogPost.Created DESC
			LIMIT ?, ?;
		');
		$query->bindValue(1, $subdomain, \PDO::PARAM_STR);
		$query->bindValue(2, intval($offset), \PDO::PARAM_INT);
		$query->bindValue(3, intval($count), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	function count($subdomain)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				COUNT(*)
			FROM
				Blog
				INNER JOIN BlogPost
					ON Blog.BlogID = BlogPost.BlogID
			WHERE
				Blog.ACTIVE = TRUE
				AND Blog.Subdomain = ?;
		');
		$query->bindValue(1, $subdomain, \PDO::PARAM_STR);
	
		$query->execute();
		return intval($query->fetchColumn());
	}
	
	function get($subdomain, $blogPostID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				BlogPost.*
			FROM
				Blog
				INNER JOIN BlogPost
					ON Blog.BlogID = BlogPost.BlogID
			WHERE
				Blog.ACTIVE = TRUE
				AND Blog.Subdomain = ?
				AND BlogPost.BlogPostID = ?;
		');
		$query->bindValue(1, $subdomain, \PDO::PARAM_STR);
		$query->bindValue(1, intval($blogPostID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}
}
?>