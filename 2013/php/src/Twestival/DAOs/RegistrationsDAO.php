<?php namespace Twestival\DAOs;

class RegistrationsDAO extends BaseDAO
{
	function create($year, $reRegistration, $name, $twitterName, $emailAddress, $city, $stateProvince, $country, $preferredTwestivalName, $charityDescription, $comment)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO Registration (Year, ReRegistration, Name, TwitterName, EmailAddress, City, StateProvince, Country, PreferredTwestivalName, CharityDescription, Comment)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
		');
		$query->bindValue(1, intval($year), \PDO::PARAM_INT);
		$query->bindValue(2, $this->toBoolean($reRegistration), \PDO::PARAM_INT);
		$query->bindValue(3, $this->trimToNull($name), \PDO::PARAM_STR);
		$query->bindValue(4, $this->trimToNull($twitterName), \PDO::PARAM_STR);
		$query->bindValue(5, $this->trimToNull($emailAddress), \PDO::PARAM_STR);
		$query->bindValue(6, $this->trimToNull($city), \PDO::PARAM_STR);
		$query->bindValue(7, $this->trimToNull($stateProvince), \PDO::PARAM_STR);
		$query->bindValue(8, $this->trimToNull($country), \PDO::PARAM_STR);
		$query->bindValue(9, $this->trimToNull($preferredTwestivalName), \PDO::PARAM_STR);
		$query->bindValue(10, $this->trimToNull($charityDescription), \PDO::PARAM_STR);
		$query->bindValue(11, $this->trimToNull($comment), \PDO::PARAM_STR);
		
		$query->execute();
		return $query->rowCount();
	}
	
	function items($year, $approvalStatus)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				Registration.*
			FROM
				Registration
			WHERE
				Registration.Year = ?
				AND Registration.ApprovalStatus = ?
			ORDER BY
				Registration.Created DESC;
		');
		$query->bindValue(1, intval($year), \PDO::PARAM_INT);
		$query->bindValue(2, $approvalStatus, \PDO::PARAM_STR);
	
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}

	function get($registrationID)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				Registration.*
			FROM
				Registration
			WHERE
				Registration.RegistrationID = ?;
		');
		$query->bindValue(1, intval($registrationID), \PDO::PARAM_INT);
	
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}
	
	function setApprovalStatus($registrationID, $approvalStatus)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				Registration
			SET
				Registration.ApprovalStatus = ?
			WHERE(Year, ReRegistration, Name, TwitterName, EmailAddress, City, StateProvince, Country, PreferredTwestivalName, CharityDescription, Comment)
				Registration.RegistrationID = ?;
		');
		$query->bindValue(1, $this->trimToNull($approvalStatus), \PDO::PARAM_STR);
		$query->bindValue(2, intval($registrationID), \PDO::PARAM_INT);
		
		$query->execute();
		return $query->rowCount();
	}
}
?>