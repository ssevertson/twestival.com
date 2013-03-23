<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/page-content/{pageName}/{contentName}
 */
class GlobalAdminPageContentResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function showEditor($pageName, $sectionName)
	{
		return $this->renderMustacheHeaderFooter('Editable/' . ucwords(strtolower($pageName)), array(
				'PageContent' => $this->container['service.page']->getPageContent($pageName, $sectionName),
				'Editable' => TRUE
		));
	}
	
	/**
	 * @method post
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function save($pageName, $sectionName)
	{
		$this->container['service.page']->updatePageContent($pageName, $sectionName, $_POST['Content']);
		
		throw new \Twestival\RedirectException('/admin');
	}
}
?>