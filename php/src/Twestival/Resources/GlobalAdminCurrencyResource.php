<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/currency
 */
class GlobalAdminCurrencyResource extends BaseResource
{
	/**
	 * @method post
	 */
	function updateExchangeRates()
	{
		$this->container['service.currency']->updateExchangeRates();
		throw new \Twestival\RedirectException("/admin");
	}
}
?>