<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/charity
 */
class BlogAdminCharityListResource extends BaseBlogResource
{
	/*
	 * Not really a list resource in the current implementation - only one charity supported per event.
	 * But, the intention is to eventually support multiple charities, so code/database is structured as such.
	 */
	
	/**
	 * @method get
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function showEditor()
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
		$event = &$blog['Event'];
		if(!$event['Charities'])
		{
			$event['Charities'] = array(array());
		}
		return $this->renderMustacheHeaderFooter('Blog/Admin/Charity/Edit', $blog);
	}
	
	/**
	 * @method put
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function create()
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
		
		$upload = new Files\EventCharityUpload($this->container, 'Image');
		$result = $upload->process($_FILES);
		
		$imageFilename = $_POST['ImageFilename'];
		if(isset($result['Image']) && isset($result['Image']['Filename']))
		{
			$imageFilename = $result['Image']['Filename'];
		}
		
		$this->container['service.event.charity']->create(
				$blog['EventID'],
				$_POST['Name'],
				$_POST['Uri'],
				$imageFilename
		);
		
		throw new \Twestival\RedirectException("/admin");
	}
}
?>