<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/team/{eventTeamMemberID}
 */
class BlogAdminTeamItemResource extends BaseBlogResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function showEditor($eventTeamMemberID)
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
		return $this->renderMustacheHeaderFooter('Blog/Admin/Team/Edit', $blog);
	}
	
	/**
	 * @method post
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function modify($eventTeamMemberID)
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
		
		if($_POST['Operation'] == '↑')
		{
			$this->container['service.event.teamMember']->moveUp($blog['EventID'], intval($eventTeamMemberID));
		}
		else if($_POST['Operation'] == '↓')
		{
			$this->container['service.event.teamMember']->moveDown($blog['EventID'], intval($eventTeamMemberID));
		}
		else if($_POST['Operation'] == '⟳')
		{
			$this->container['service.event.teamMember']->updateTwitterImage($blog['EventID'], intval($eventTeamMemberID));
		}
		throw new \Twestival\RedirectException("/admin/team");
	}

	/**
	 * @method delete
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function delete($eventTeamMemberID)
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
	
		$this->container['service.event.teamMember']->delete($blog['EventID'], intval($eventTeamMemberID));
		
		throw new \Twestival\RedirectException("/admin/team");
	}
}
?>