<?php namespace Twestival\Resources;

use Twestival\Services\GlobalStatsService;
/**
 * @namespace global
 * @uri /test
 */
class GlobalHomeResource extends BaseResource
{
	/**
	 * @method get
	 */
	function html() {
		return $this->renderMustache('GlobalHome');
	}
}
?>