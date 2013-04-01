<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/event/image
 */
class BlogAdminEventItemImageResource extends BaseBlogResource
{
	/**
	 * @method post
	 * @provides application/json
	 * @requireCurrentBlogEventAdmin
	 */
	function upload()
	{
		$upload = new Files\EventUpload($this->container, 'Image');
		$result = $upload->process($_FILES);
	
		return json_encode($result);
	}
}
?>