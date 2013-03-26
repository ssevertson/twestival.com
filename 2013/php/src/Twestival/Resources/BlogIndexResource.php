<?php namespace Twestival\Resources;

/**
 * @namespace blog
 * @uri /index
 */
class BlogIndexResource extends BaseResource
{
	const BLOG_POSTS_PER_PAGE = 3;
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
		
		$pages = array(
				'Current' => $page
		);
		if($postCount > ($offset + BlogIndexResource::BLOG_POSTS_PER_PAGE))
		{
			$pages['Older'] = ($page + 1);
		}
		if($page > 1)
		{
			$pages['Newer'] = ($page - 1);
		}
		$data['Pages'] = $pages;
		
		$event = $this->container['service.event']->getEvent($data['EventID']);
		if($event['FundraisingGoalUSD'])
		{
			$event['FundraisingGoalRatio'] = $event['DonationTotalUSD'] / $event['FundraisingGoalUSD'];
		}
		$data['Event'] = $event;
		
		$data['BlogPosts'] = $this->container['service.blog.post']->getPosts(
				$subdomain,
				BlogIndexResource::BLOG_POSTS_PER_PAGE,
				$offset);
		
		$data['EventCharities'] = $this->container['service.event.charity']->getCharities($data['EventID']);
		
		$data['EventTeamMembers'] = $this->container['service.event.teamMember']->getTeamMembers($data['EventID']);
		
		$eventSponsors = $this->container['service.event.sponsor']->getSponsors($data['EventID']);
		$data['EventSponsorRows'] = $this->toGrid($eventSponsors, 2);
		
		return $this->renderMustacheHeaderFooter('Blog/Index',
				$data);
	}
}
?>