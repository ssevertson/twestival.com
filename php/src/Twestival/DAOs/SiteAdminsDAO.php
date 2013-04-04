<?php namespace Twestival\DAOs;

class SiteAdminsDAO extends BaseDAO
{
	function authenticate($username, $password)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				SiteAdmin.SiteAdminID
			FROM
				SiteAdmin
			WHERE
				SiteAdmin.Username = ?
				AND SiteAdmin.Password = UPPER(SHA1(CONCAT(?, SiteAdmin.Salt)));
		');
		$query->bindValue(1, $username, \PDO::PARAM_STR);
		$query->bindValue(2, $password, \PDO::PARAM_STR);
		$query->execute();
		return intval($query->fetchColumn());
	}
}
?>