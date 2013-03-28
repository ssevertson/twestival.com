<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/charity/{eventCharityID}
 */
class BlogAdminCharityItemResource extends BaseBlogResource
{
	/**
	 * @method put
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function update($eventCharityID)
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
		
		$upload = new Files\EventCharityUpload($this->container, 'Image');
		$result = $upload->process($_FILES);
		
		$imageFilename = $_POST['ImageFilename'];
		if(isset($result['Image']) && isset($result['Image']['Filename']))
		{
			$imageFilename = $result['Image']['Filename'];
		}
		
		$this->container['service.event.charity']->update(
				$blog['EventID'],
				intval($eventCharityID),
				$_POST['Name'],
				$_POST['Uri'],
				$imageFilename);
		
		throw new \Twestival\RedirectException("/admin");
	}
}
?>