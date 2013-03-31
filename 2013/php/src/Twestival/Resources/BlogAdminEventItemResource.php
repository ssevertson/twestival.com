<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/event
 */
class BlogAdminEventItemResource extends BaseBlogResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function showEditor()
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
		return $this->renderMustacheHeaderFooter('Blog/Admin/Sponsor/Edit', $blog);
	}
	
	/**
	 * @method put
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function save()
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
	
		$upload = new Files\EventSponsorUpload($this->container, 'Image');
		$result = $upload->process($_FILES);
		
		$imageFilename = $_POST['ImageFilename'];
		if(isset($result['Image']) && isset($result['Image']['Filename']))
		{
			$imageFilename = $result['Image']['Filename'];
		}
		
		$this->container['service.event']->saveEventAdminFields(
				$blog['EventID'],
				$imageFilename,
				$_POST['FundraisingGoalUSD'],
				$_POST['DonationTotalUSD'],
				$_POST['AttendUri'],
				$_POST['DonateUri'],
				$_POST['Description'],
				strtotime($_POST['Date']),
				$_POST['StartTime'],
				$_POST['EndTime'],
				$_POST['LocationName'],
				$_POST['LocationAddress1'],
				$_POST['LocationAddress2'],
				$_POST['LocationUri'],
				$_POST['OrganizerEmailAddress'],
				$_POST['TwitterName'],
				$_POST['FacebookUri'],
				$_POST['TwitterShareMessage']
		);
	
		throw new \Twestival\RedirectException("/admin");
	}
}
?>