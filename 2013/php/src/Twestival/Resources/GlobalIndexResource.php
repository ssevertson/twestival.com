<?php namespace Twestival\Resources;

use Twestival\Services\PromotionService;
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
		$promotions = new PromotionService($this->container);
		return $this->renderMustacheHeaderFooter('GlobalIndex', array(
			'PrimaryPromotions' => $promotions->getForPageSection('HOME', 'PRIMARY'),
			'SecondaryPromotions' => $promotions->getForPageSection('HOME', 'SECONDARY'),
		));
	}
}
?>