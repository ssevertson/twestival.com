<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/sponsor
 */
class BlogAdminSponsorListResource extends BaseBlogResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function showList()
	{
		$subdomain = $this->container['request.subdomain'];
		
		$blog = $this->container['service.blog']->getBySubdomain($subdomain);
		
		$sponsors = &$blog['Event']['Sponsors'];
		$sponsors[0]['First'] = TRUE;
		$sponsors[count($sponsors) - 1]['Last'] = TRUE;
		
		return $this->renderMustacheHeaderFooter('Blog/Admin/Sponsor/List', $blog);
	}
	

	/**
	 * @method put
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function create()
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
	
		$upload = new Files\EventSponsorUpload($this->container, 'Image');
		$result = $upload->process($_FILES);
		
		$imageFilename = $_POST['ImageFilename'];
		if(isset($result['Image']) && isset($result['Image']['Filename']))
		{
			$imageFilename = $result['Image']['Filename'];
		}
		
		$this->container['service.event.sponsor']->create(
				$blog['EventID'],
				$_POST['Name'],
				$_POST['Uri'],
				$imageFilename
		);
	
		throw new \Twestival\RedirectException("/admin/sponsor");
	}
}
?>