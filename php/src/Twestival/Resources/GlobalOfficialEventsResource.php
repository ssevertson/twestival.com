<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /events
 */
class GlobalOfficialEventsResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();
		
		$continents = $this->container['service.event']->getByContinent($currentYear);
		
		$continentsList = array();
		foreach($continents as $continent => &$events)
		{
			array_push($continentsList, array(
				'Continent' => $continent,
				'CountryCount' => $this->countUniqueFieldValues($events, 'LocationCountry'),
				'CityCount' => $this->countUniqueFieldValues($events, 'LocationCity'),
				'Events' => $events
			));
		}
		
		return $this->renderMustacheHeaderFooter('Global/OfficialEvents', array(
				'Continents' => $continentsList
		));
	}
	
	/**
	 * @method get
	 * @provides application/javascript
	 * @priority 2
	 */
	function search()
	{
		if(!isset($_GET['q']))
		{
			return;
		}
		$q = $_GET['q'];
		
		$currentYear = $this->container['service.year']->getMostRecentActiveYear();
		$events = $this->container['service.event']->search($currentYear, $q);
		$json = json_encode($events);
		
		return isset($_GET['callback'])
				? $_GET['callback'] . '(' . $json . ')'
				: $json;
	}
	
	private function countUniqueFieldValues(&$array, $field)
	{
		$unique = array();
		foreach($array as $item)
		{
			$unique[$item[$field]] = TRUE;
		}
		return count($unique);
	}
}
?>