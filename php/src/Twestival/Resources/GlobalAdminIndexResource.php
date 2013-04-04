<?php namespace Twestival\Resources;


/**
 * @namespace global
 * @uri /admin
 */
class GlobalAdminIndexResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function showMenu()
	{
		return $this->renderMustacheHeaderFooter('Global/Admin/Index', array(
				'PageSections' => $this->container['service.page']->getPageSections(),
				'PageContents' => $this->container['service.page']->getPageContents(),
				'Registrations' => $this->container['service.registration']->getNewForCurrentYear()
		));
	}
}
?>