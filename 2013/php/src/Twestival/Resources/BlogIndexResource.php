<?php namespace Twestival\Resources;

use Twestival\Services\GlobalStatsService;
/**
 * @namespace blog
 * @uri /index
 */
class BlogIndexResource extends BaseResource
{
	/**
	 * @method get
	 */
	function html() {
		$globalStatsService = new GlobalStatsService($this->container);
		$summaryStats = $globalStatsService->getSummaryStats();
		return $this->renderMustache('Header', $summaryStats);
	}
}
?>