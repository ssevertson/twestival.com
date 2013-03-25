<?php namespace Twestival\Resources;

use Twestival\Services\BlogService;
/**
 * @namespace global
 * @uri /blog
 * @uri /blog/{subdomain}
 */
class BlogResource extends BaseResource
{
	/**
	 * @method get
	 * @provides application/json
	 */
	function json($subdomain = '')
	{
		$blogs = $this->container['service.blog'];
		
		if(!$subdomain)
		{
			return json_encode($blogs->getBlogs());
		}
		else
		{
			return json_encode($blogs->getBySubdomain($subdomain));
		}
	}
}
?>