<?php namespace Twestival\Resources;

use Twestival\Services\BlogService;
/**
 * @namespace global
 * @uri /blog
 * @uri /blog/{blogIDOrSubdomain}

 */
class BlogResource extends BaseResource
{
	/**
	 * @method get
	 * @provides application/json
	 */
	function json($blogIDOrSubdomain = '')
	{
		$blogs = $this->container['service.blog'];
		
		if(!$blogIDOrSubdomain)
		{
			$currentYear = $this->container['service.year']->getMostRecentActiveYear();
			return json_encode($blogs->getBlogs($currentYear));
		}
		else if(is_numeric($blogIDOrSubdomain))
		{
			return json_encode($blogs->getById(intval($blogIDOrSubdomain)));
		}
		else
		{
			return json_encode($blogs->getBySubdomain($blogIDOrSubdomain));
		}
	}

}
?>