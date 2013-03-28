<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /admin/team
 */
class BlogAdminTeamListResource extends BaseBlogResource
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
		
		$teamMembers = &$blog['Event']['TeamMembers'];
		$teamMembers[0]['First'] = TRUE;
		$teamMembers[count($teamMembers) - 1]['Last'] = TRUE;
		
		return $this->renderMustacheHeaderFooter('Blog/Admin/Team/List',
				$blog);
	}
	

	/**
	 * @method put
	 * @provides text/html
	 * @requireCurrentBlogEventAdmin
	 */
	function create()
	{
		$blog = $this->container['service.blog']->getBySubdomain($this->container['request.subdomain']);
	
		$this->container['service.event.teamMember']->create(
				$blog['EventID'],
				$_POST['TwitterName']);
	
		throw new \Twestival\RedirectException("/admin/team");
	}
}
?>