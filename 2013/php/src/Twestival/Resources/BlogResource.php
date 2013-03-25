<?php namespace Twestival\Resources;

use Twestival\Services\BlogService;
/**
 * @namespace global
 * @uri /blog
 * @uri /blog/:id

 */
class BlogResource extends BaseResource
{
	/**
	 * @method get
     * @param  int $aBlogID
     * @return str
	 */
	function html($aBlogID = '') {
		$blogService = new BlogService($this->container);
		
		if ($aBlogID == '')
			return $blogService->getBlogs();
		else {
			return $blogService->getBlog($aBlogID);
		}
	}

}
?>