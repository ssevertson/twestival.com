<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/event-promotions/{pageName}/{sectionName}/{sequence}/image
 */
class GlobalAdminEventPromotionItemImageResource extends BaseResource
{
	/**
	 * @method post
	 * @provides application/json
	 * @requireSiteAdmin
	 */
	function upload($pageName, $sectionName, $sequence)
	{
		$upload = new Files\EventPromotionUpload($this->container, $pageName, $sectionName, 'Image');
		$result = $upload->process($_FILES);
		
		return json_encode($result);
	}
}
?>