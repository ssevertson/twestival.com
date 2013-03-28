<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin
 * @uri /admin/index
 */
class BlogAdminIndexResource extends BaseBlogResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);

		return $this->renderMustacheHeaderFooter('Blog/Admin/Index', $blog);
	}
}
?>