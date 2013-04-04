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
		
		$teamMember = $this->container['service.event.teamMember']->getTeamMember(
				$blog['EventID'],
				intval($eventTeamMemberID));
		$blog['Event']['TeamMembers'] = array($teamMember);
		
		return $this->renderMustacheHeaderFooter('Blog/Admin/Team/Edit', $blog);
	}
	
	/**
	 * @method put
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function update($eventTeamMemberID)
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
	
		$this->container['service.event.teamMember']->update(
				$blog['EventID'],
				intval($eventTeamMemberID),
				$_POST['TwitterName']);
	
		throw new \Twestival\RedirectException("/admin/team");
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