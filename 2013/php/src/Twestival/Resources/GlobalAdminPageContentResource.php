<?php namespace Twestival\Resources;

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
	function showEditor($pageName, $contentName)
	{
		return $this->renderMustacheHeaderFooter('Global/Editable/' . ucwords(strtolower($pageName)), array(
				'PageContent' => $this->container['service.page']->getPageContent($pageName, $contentName),
				'Editable' => TRUE
		));
	}
	
	/**
	 * @method post
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function save($pageName, $contentName)
	{
		$this->container['service.page']->updatePageContent($pageName, $contentName, $_POST['Content']);
		
		throw new \Twestival\RedirectException('/admin');
	}
}
?>