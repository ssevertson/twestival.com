<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/sponsor/{eventSponsorID}
 */
class BlogAdminSponsorItemResource extends BaseBlogResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function showEditor($eventSponsorID)
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
		
		$sponsor = $this->container['service.event.sponsor']->getSponsor(
				$blog['EventID'],
				intval($eventSponsorID));
		$blog['Event']['Sponsors'] = array($sponsor);
		
		return $this->renderMustacheHeaderFooter('Blog/Admin/Sponsor/Edit', $blog);
	}
	
	/**
	 * @method put
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function update($eventSponsorID)
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
	
		$upload = new Files\EventSponsorUpload($this->container, 'Image');
		$result = $upload->process($_FILES);
		
		$imageFilename = $_POST['ImageFilename'];
		if(isset($result['Image']) && isset($result['Image']['Filename']))
		{
			$imageFilename = $result['Image']['Filename'];
		}
		
		$this->container['service.event.sponsor']->update(
				$blog['EventID'],
				intval($eventSponsorID),
				$_POST['Name'],
				$_POST['Uri'],
				$imageFilename
		);
	
		throw new \Twestival\RedirectException("/admin/sponsor");
	}
	
	/**
	 * @method post
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function modify($eventSponsorID)
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
		
		if($_POST['Operation'] == '↑')
		{
			$this->container['service.event.sponsor']->moveUp($blog['EventID'], intval($eventSponsorID));
		}
		else if($_POST['Operation'] == '↓')
		{
			$this->container['service.event.sponsor']->moveDown($blog['EventID'], intval($eventSponsorID));
		}
		throw new \Twestival\RedirectException("/admin/sponsor");
	}

	/**
	 * @method delete
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function delete($eventSponsorID)
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
	
		$this->container['service.event.sponsor']->delete($blog['EventID'], intval($eventSponsorID));
		
		throw new \Twestival\RedirectException("/admin/sponsor");
	}
}
?>