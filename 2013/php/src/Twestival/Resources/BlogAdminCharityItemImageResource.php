<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/charity/image
 */
class BlogAdminCharityItemImageResource extends BaseBlogResource
{
	/**
	 * @method post
	 * @provides application/json
	 * @requireCurrentBlogEventAdmin
	 */
	function upload()
	{
		$upload = new Files\EventCharityUpload($this->container, 'Image');
		$result = $upload->process($_FILES);
	
		return json_encode($result);
	}
}
?>