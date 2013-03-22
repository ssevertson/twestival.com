<?php namespace Twestival\Resources;

use Twestival\Services\GlobalStatsService;
/**
 * @namespace global
 * @uri /index
 */
class GlobalIndexResource extends BaseResource
{
	/**
	 * @method get
	 */
	function html() {
		return $this->renderMustache('GlobalHome');
	}
}
?>