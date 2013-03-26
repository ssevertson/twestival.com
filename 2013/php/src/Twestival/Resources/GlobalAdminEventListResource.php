<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/event
 */
class GlobalAdminEventListResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function showList()
	{
		$years = $this->container['service.year']->getYears();
		$year = isset($_GET['Year'])
				? $_GET['Year']
				: $this->container['service.year']->getMostRecentActiveYear();
		$this->selectByField($year, 'Year', $years);
		
		$active = isset($_GET['Active'])
				? $_GET['Active'] === 'true'
				: 'true';
		
		$events = $this->container['service.event']->getEvents($year, $active);
		
		return $this->renderMustacheHeaderFooter('Global/Admin/Event/List', array(
				'Years' => $years,
				'Active' => $active,
				'Events' => $events
		));
	}
	
	/**
	 * @method post
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function temp()
	{
		$events = $this->container['service.event']->getEvents(2011, true);
		array_merge($events, $this->container['service.event']->getEvents(2011, true));
		print "<html><head><meta charset=\"utf-8\" /></head><body>";
		foreach($events as $event)
		{
			$eventID = $event['EventID'];
			$locationID = 0;
			
			switch($eventID)
			{
				case 1: $locationID = 4180439; break;
				case 2: $locationID = 4671654; break;
				case 3: $locationID = 1650357; break;
				case 4: $locationID = 1277333; break;
				case 5: $locationID = 276781; break;
				case 7: $locationID = 3128026; break;
				case 8: $locationID = 750598; break;
				case 10: $locationID = 2655562; break;
				case 12: $locationID = 3469058; break;
				case 13: $locationID = 2654710; break;
				case 14: $locationID = 2654675; break;
				case 9: $locationID = 2655603; break;
				case 15: $locationID = 3435910; break;
				case 16: $locationID = 5913490; break;
				case 17: $locationID = 2653261; break;
				case 18: $locationID = 4887398; break;
				case 19: $locationID = 4381982; break;
				case 20: $locationID = 2618425; break;
				case 21: $locationID = 2638853; break;
				case 56: $locationID = 1261481; break;
				case 22: $locationID = 5419384; break;
				case 23: $locationID = 4853828; break;
				case 24: $locationID = 4990729; break;
				case 25: $locationID = 297090; break;
				case 26: $locationID = 290030; break;
				case 27: $locationID = 292223; break;
				case 28: $locationID = 5946768; break;
				case 29: $locationID = 2649660; break;
				case 30: $locationID = 5881791; break;
				case 31: $locationID = 2648579; break;
				case 32: $locationID = 4699066; break;
				case 33: $locationID = 1269843; break;
				case 34: $locationID = 745044; break;
				case 35: $locationID = 1642911; break;
				case 36: $locationID = 105343; break;
				case 37: $locationID = 281184; break;
				case 38: $locationID = 4393217; break;
				case 39: $locationID = 1733432; break;
				case 42: $locationID = 4922462; break;
				case 43: $locationID = 2988507; break;
				case 44: $locationID = 2644210; break;
				case 45: $locationID = 3196359; break;
				case 46: $locationID = 2643743; break;
				case 41: $locationID = 5368361; break;
				case 47: $locationID = 3117735; break;
				case 48: $locationID = 2643123; break;
				case 50: $locationID = 2995469; break;
				case 51: $locationID = 3530597; break;
				case 52: $locationID = 3173435; break;
				case 53: $locationID = 6077243; break;
				case 54: $locationID = 524901; break;
				case 55: $locationID = 1275339; break;
				case 58: $locationID = 5128581; break;
				case 59: $locationID = 4544349; break;
				case 60: $locationID = 2641022; break;
				case 61: $locationID = 6094817; break;
				case 62: $locationID = 2640729; break;
				case 63: $locationID = 2988507; break;
				case 64: $locationID = 1735106; break;
				case 65: $locationID = 4560349; break;
				case 67: $locationID = 2640194; break;
				case 69: $locationID = 5134086; break;
				case 70: $locationID = 3838583; break;
				case 71: $locationID = 5809844; break;
				case 75: $locationID = 2646057; break;
				case 76: $locationID = 293397; break;
				case 6: $locationID = 3405870; break;
				case 40: $locationID = 1735161; break;
				case 49: $locationID = 1701668; break;
				case 57: $locationID = 5101798; break;
				case 66: $locationID = 5308655; break;
				case 68: $locationID = 1259229; break;
				case 73: $locationID = 1880252; break;
				case 72: $locationID = 5391959; break;
				case 77: $locationID = 2641673; break;
				case 78: $locationID = 1622846; break;
				case 79: $locationID = 2028462; break;
				case 80: $locationID = 6173331; break;
				case 81: $locationID = 6174041; break;
				case 82: $locationID = 4140963; break;
			}
			
			if($locationID == 0)
			{
				print "No mapping for event: " . $event['EventID'] . "<br/>";
			}
			else
			{
				$this->container['service.location']->saveEventLocationCity($eventID, $locationID);
			}
			
// 			$registration = $this->container['service.registration']->get($event['RegistrationID']);
// 			$locations = $this->container['service.location']->searchForCity($registration['City'], $registration['StateProvince'], $registration['Country']);
// 			print "<html><head><meta charset=\"utf-8\" /></head><body>";
// 			switch(count($locations))
// 			{
// 				case 0:
// 					print "Could not find location for " . $event['EventID'] . ": City: " . $registration['City'] . ", State: " . $registration['StateProvince'] . ", Country: " . $registration['Country'] . "<br/>\n";
// 					break;
// 				case 1:
// 					print $event['EventID'] . "|" . $locations[0]['GeonameID'] . "<br/>\n";
// 					break;
// 				default:
// 					print "Found multiple locations for " . $event['EventID'] . ": City: " . $registration['City'] . ", State: " . $registration['StateProvince'] . ", Country: " . $registration['Country'] . "<br/>\n";
// 					print $event['EventID'] . "|" . $locations[0]['GeonameID'] . "<br/>\n";
// 					foreach($locations as $location)
// 					{
// 						print "&nbsp;&nbsp;*&nbsp; " . $location['GeonameID'] . ": " . $location['Name'] . "<br/>\n";
// 					}
// 					break;
// 			}
		}
		$this->container['connection']->commit();
		print "Done";
		print "</body></html>";
		exit();
	}
}
?>