<?php namespace Twestival\DAOs;

class YearsDAO extends BaseDAO
{
	function getMostRecentActiveYear()
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				MAX(Year.Year)
			FROM
				Year
			WHERE
				Year.Active = TRUE;
		');
		$query->execute();
		return intval($query->fetchColumn());
	}
	
	function items()
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				*
			FROM
				Year
			ORDER BY
				Year.Year DESC;
		');
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
}
?>