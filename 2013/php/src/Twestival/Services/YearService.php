<?php namespace Twestival\Services;

class YearService extends BaseService
{
	function getMostRecentActiveYear()
	{
		$years = $this->container['dao.years'];
		return 2011; //HACK HACK HACK: Use $years->getMostRecentActiveYear(); when we have enough non-historical data
	}
	
	function getYears()
	{
		$years = $this->container['dao.years'];
		return $years->items();
	}
}
?>