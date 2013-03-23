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
		$pages = new PageService($this->container);
		
		return $this->renderMustacheHeaderFooter('Editable/' . strtoupper($pageName), array(
				'PageContent' => $pages->getPageContent($pageName, $sectionName),
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
		$pages = new PageService($this->container);
	
		$pages->updatePageContent($pageName, $sectionName, $_POST['Content']);
		
		throw new \Twestival\RedirectException('/admin');
	}
}
?>