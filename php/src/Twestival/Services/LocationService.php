<?php namespace Twestival\Services;

class LocationService extends BaseService
{
	function searchForCity($city, $stateProvince, $country, $count=10)
	{
		$result = $this->performSearch(
				$this->formatCity($city, $stateProvince, $country),
				$count);
		if(!$result)
		{
			$result = $this->performSearch(
					$this->formatCity($city, '', $country),
					$count);
		}
		if(!$result && $stateProvince)
		{
			$result = $this->performSearch(
					$this->formatCity($city, $stateProvince, ''),
					$count);
		}
		if(!$result)
		{
			$result = $this->performSearch(
					$this->formatCity($city, '', ''),
					$count);
		}
		return $result;
	}
	private function performSearch($search, $count)
	{
		$client = $this->container['geonames.client'];
		$request = $client->get('searchJSON?username={username}&lang={lang}');
		$request->getQuery()->merge(array(
				'q' => $search,
				'featureClass' => 'P',
				'maxRows' => $count,
				'style' => 'medium'
		));
		$response = $request->send();
		$data = $response->json();
		
		$results = array();
		foreach($data['geonames'] as $result)
		{
			array_push($results, array(
			'LocationID' => $result['geonameId'],
			'Name' => $this->formatCity($result['name'], $result['adminName1'], $result['countryName']),
			'Latitude' => $result['lat'],
			'Longitude' => $result['lng']
			));
		}
		return $results;
	}
	private function formatCity($city, $stateProvince, $country)
	{
		$result = '';
		if($city)
		{
			$result .= $city;
		}
		if($stateProvince)
		{
			if($result)
			{
				$result .= ', ';
			}
			$result .= $stateProvince;
		}
		if($country)
		{
			if($result)
			{
				$result .= ', ';
			}
			$result .= $country;
		}
			
		return $result;
	}
	
	function saveEventLocationCity($eventID, $locationID)
	{
		$client = $this->container['geonames.client'];
		$request = $client->get('hierarchyJSON?username={username}&lang={lang}');
		$request->getQuery()->merge(array(
				'geonameId' => $locationID,
				'style' => 'full'
		));
		$response = $request->send();
		$data = $response->json();
		
		$this->container['dao.event.locations']->delete($eventID);
		
		foreach($data['geonames'] as $result)
		{
			$locationType = $this->geonamesFCodeToLocationType($result['fcode']);
			if($locationType)
			{
				$locationID = $result['geonameId'];
				$this->saveLocation(
						$locationID,
						$this->getISO3166Code($locationType, $result),
						$locationType,
						$result['name'],
						$result['population'],
						$result['lat'],
						$result['lng']);
				
				$this->container['dao.event.locations']->create(
						$eventID,
						$locationID);
			}
		}
	}
	
	private function geonamesFCodeToLocationType($fcode)
	{
		if($fcode == 'CONT')
		{
			return 'CONTINENT';
		}
		else if(!strncmp($fcode, 'PCL', 3))
		{
			return 'COUNTRY';
		}
		else if($fcode == 'ADM1')
		{
			return 'STATE_PROVINCE';
		}
		else if(!strncmp($fcode, 'PPL', 3))
		{
			return 'CITY';
		}
		return NULL; // Ignore
	}
	
	private function getISO3166Code($locationType, $result)
	{
		switch($locationType)
		{
			case 'CONTINENT':
				return $result['continentCode'];
			case 'COUNTRY':
				return $result['countryCode'];
			case 'STATE_PROVINCE':
				return $result['adminCode1'];
			default:
				return NULL;
		}
	}
	
	function saveLocation($locationID, $iso3166Code, $type, $name, $population, $latitude, $longitude)
	{
		$existing = $this->container['dao.locations']->get($locationID);
		if($existing)
		{
			if($iso3166Code)
			{
				$this->container['dao.locations']->updateISO3166Code($locationID, $iso3166Code);
				
			}
			
			if($population)
			{
				$this->container['dao.locations']->updatePopulation($locationID, $population);
			}
		}
		else
		{
			$this->container['dao.locations']->create($locationID, $iso3166Code, $type, $name, $population, $latitude, $longitude, $iso3166Code);
		}
	}
	
	function getLocationTotalsByType($year, $locationType)
	{
		$results = $this->container['dao.locations']->getLocationTotalsByType($year, $locationType);
		return $this->toNumerics($results);
	}
	function getLocationTotalsByTypeAndID($year, $locationType, $locationID)
	{
		$results = $this->container['dao.locations']->getLocationTotalsByTypeAndID($year, $locationType, $locationID);
		return $this->toNumerics($results);
	}
	
	private function toNumerics(&$results)
	{
		foreach($results as &$result)
		{
			$result['LocationID'] = intval($result['LocationID']);
			$result['Population'] = intval($result['Population']);
			$result['Latitude'] = floatval($result['Latitude']);
			$result['Longitude'] = floatval($result['Longitude']);
			$result['DonationTotalUSD'] = intval($result['DonationTotalUSD']);
		}
		return $results;
	}
}
?>