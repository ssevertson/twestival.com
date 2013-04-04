<?php namespace Twestival\Services;

class LocationService extends BaseService
{
	function searchForCity($city, $stateProvince, $country, $count=10)
	{
		$client = $this->container['geonames.client'];
		$request = $client->get('searchJSON?username={username}&lang={lang}');
		$request->getQuery()->merge(array(
				'q' => $this->formatCity($city, $stateProvince, $country),
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
				'style' => 'medium'
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
	
	function saveLocation($locationID, $type, $name, $population, $latitude, $longitude)
	{
		$existing = $this->container['dao.locations']->get($locationID);
		if($existing)
		{
			if($population)
			{
				$this->container['dao.locations']->updatePopulation($locationID, $population);
			}
		}
		else
		{
			$this->container['dao.locations']->create($locationID, $type, $name, $population, $latitude, $longitude);
		}
	}
}
?>