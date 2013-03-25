<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /index
 */
class BlogIndexResource extends BaseResource
{
	const BLOG_POSTS_PER_PAGE = 10;
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		$subdomain = $this->container['request.subdomain'];
		
		$data = $this->container['service.blog']->getBySubdomain($subdomain);

		$page = isset($_GET['Page'])
				? intval($_GET['Page'])
				: 1;
		$offset = ($page - 1) * BlogIndexResource::BLOG_POSTS_PER_PAGE;
		$postCount = $this->container['service.blog.post']->getPostCount($subdomain);
		$data['Pages'] = array(
				'Page' => $page,
				'PageNext' => $postCount > ($offset + BlogIndexResource::BLOG_POSTS_PER_PAGE),
				'PagePrevious' => ($page > 1)
		);
		
		$data['BlogPosts'] = $this->container['service.blog.post']->getPosts(
				$subdomain,
				BlogIndexResource::BLOG_POSTS_PER_PAGE,
				$offset);
		
		$data['EventCharities'] = $this->container['service.event.charity']->getCharities(
				$data['EventID']);
		
		$data['EventTeamMembers'] = $this->container['service.event.teamMember']->getTeamMembers(
				$data['EventID']);
		
		$data['EventSponsors'] = $this->container['service.event.sponsor']->getSponsors(
				$data['EventID']);
		
		return $this->renderMustacheHeaderFooter('Blog/Index',
				$data);
	}
}
?>