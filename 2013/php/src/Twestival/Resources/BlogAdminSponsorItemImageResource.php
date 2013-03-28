<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/sponsor/image
 */
class BlogAdminSponsorItemImageResource extends BaseBlogResource
{
	/**
	 * @method post
	 * @provides application/json
	 * @requireCurrentBlogEventAdmin
	 */
	function upload()
	{
		$upload = new Files\EventSponsorUpload($this->container, 'Image');
		$result = $upload->process($_FILES);
	
		return json_encode($result);
	}
}
?>