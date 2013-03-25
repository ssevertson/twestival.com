<?php namespace Twestival\Resources;

use Twestival\Services\EventBlogService;
/**
 * @namespace global
 * @uri /eventblog
 * @uri /eventblog/:id

 */
class EventBlogResource extends BaseResource
{
	/**
	 * @method get
     * @param  int $anEventID
     * @return str
	 */
	function html($anEventID = '') {
		$eventblogService = new EventBlogService($this->container);
		
		if ($anEventID == '')
			return $eventblogService->getEventBlogs();
		else {
			return $eventblogService->getEventBlog($anEventID);
		}
	}

}
?>