<?php namespace Twestival\DAOs;

class BlogPostsDAO extends BaseDAO
{
	function items($subdomain, $count, $offset)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				BlogPost.*,
				Blog.Subdomain AS BlogSubdomain
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
	
	function get($subdomain, $postID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				BlogPost.*,
				Blog.Subdomain AS BlogSubdomain
			FROM
				Blog
				INNER JOIN BlogPost
					ON Blog.BlogID = BlogPost.BlogID
			WHERE
				Blog.ACTIVE = TRUE
				AND Blog.Subdomain = ?
				AND BlogPost.PostID = ?;
		');
		$query->bindValue(1, $subdomain, \PDO::PARAM_STR);
		$query->bindValue(2, intval($postID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}
	
	function update($subdomain, $postID, $title, $content)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				BlogPost,
				Blog
			SET
				BlogPost.Title = ?,
				BlogPost.Content = ?
			WHERE
				BlogPost.BlogID = Blog.BlogID
				AND Blog.Subdomain = ?
				AND BlogPost.PostID = ?;
		');
		$query->bindValue(1, $this->trimToNull($title), \PDO::PARAM_STR);
		$query->bindValue(2, $this->trimToNull($content), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($subdomain), \PDO::PARAM_STR);
		$query->bindValue(4, intval($postID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->rowCount();
	}
	
	function create($subdomain, $title, $content)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO BlogPost (BlogID, Title, Content)
			SELECT
				Blog.BlogID,
				?,
				?
			FROM
				Blog
			WHERE
				Blog.Subdomain = ?;
		');
		$query->bindValue(1, $this->trimToNull($title), \PDO::PARAM_STR);
		$query->bindValue(2, $this->trimToNull($content), \PDO::PARAM_STR);
		$query->bindValue(3, $this->trimToNull($subdomain), \PDO::PARAM_STR);
	
		$query->execute();
		return $conn->lastInsertId();
	}
	function delete($subdomain, $postID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			DELETE FROM
				BlogPost
			USING
				BlogPost,
				Blog
			WHERE
				BlogPost.BlogID = Blog.BlogID
				AND Blog.Subdomain = ?
				AND BlogPost.PostID = ?;
		');
		$query->bindValue(1, $this->trimToNull($subdomain), \PDO::PARAM_STR);
		$query->bindValue(2, intval($postID), \PDO::PARAM_INT);
	
		$query->execute();
		return $conn->lastInsertId();
	}
}
?>