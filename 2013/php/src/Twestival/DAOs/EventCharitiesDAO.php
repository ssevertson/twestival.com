<?php namespace Twestival\DAOs;

class EventCharitiesDAO extends BaseDAO
{
	function items($eventID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventCharity.*
			FROM
				Event
				INNER JOIN EventCharity
					ON Event.EventID = EventCharity.EventID
			WHERE
				Event.Active = TRUE
				AND EventCharity.EventID = ?
			ORDER BY
				EventCharity.Sequence;
		');
		$query->bindValue(1, intval($eventID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
}
?>