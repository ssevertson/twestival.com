<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /impact/api/{year}/{type}
 * @uri /impact/api/{year}/{type}/{locationID}
 */
class GlobalImpactAPIResource extends BaseResource
{
	/**
	 * @method get
	 * @provides application/json
	 */
	function json($year, $type, $locationID = NULL)
	{
		$result = isset($locationID) && is_numeric($locationID)
				? $this->container['service.location']->getLocationTotalsByTypeAndID(intval($year), $type, intval($locationID))
				: $this->container['service.location']->getLocationTotalsByType(intval($year), $type);
		return json_encode($result);
	}
}
?>