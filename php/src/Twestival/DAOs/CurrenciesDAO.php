<?php namespace Twestival\DAOs;

class CurrenciesDAO extends BaseDAO
{
	function items()
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				*
			FROM
				Currency
			ORDER BY
				Currency.ISO4217Code;
		');
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	function updateExchangeRate($iso4217Code, $rate)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				Currency
			SET
				Currency.ExchangeRate = ?
			WHERE
				Currency.ISO4217Code = ?;
		');
		$query->bindValue(1, floatval($rate), \PDO::PARAM_INT);
		$query->bindValue(2, $this->trimToNull($iso4217Code), \PDO::PARAM_STR);
		
		$query->execute();
		return $query->rowCount();
	}
}
?>