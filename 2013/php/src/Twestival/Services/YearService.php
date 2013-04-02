<?php namespace Twestival\Services;

class YearService extends BaseService
{
	function getMostRecentActiveYear()
	{
		$years = $this->container['dao.years'];
		return 2011; //$years->getMostRecentActiveYear();
	}
	
	function getYears()
	{
		$years = $this->container['dao.years'];
		return $years->items();
	}
}
?>