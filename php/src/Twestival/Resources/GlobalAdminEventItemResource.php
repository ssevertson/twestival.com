<?php namespace Twestival\Resources;

use \Twestival\Services\PromotionService;
use \Twestival\Services\PageService;


/**
 * @namespace global
 * @uri /admin/event/{eventID}
 */
class GlobalAdminEventItemResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function view($eventID)
	{
		$eventID = intval($eventID);
		
		$event = $this->container['service.event']->getEvent($eventID);
		
		$event['EventCharities'] = $this->container['service.event.charity']->getCharities($eventID);

		$event['EventTeamMemberCount'] = $this->container['service.event.teamMember']->countTeamMembers($eventID);
		if($event['EventTeamMemberCount'] > 1)
		{
			$event['EventTeamMembersMultiple'] = TRUE;
		}
		
		$event['EventSponsorCount'] = $this->container['service.event.sponsor']->countSponsors($eventID);
		
		$event['BlogPosts'] = $this->container['service.blog.post']->getPosts($event['BlogSubdomain'], 1, 0);
		if($event['BlogPosts'])
		{
			$blogPost = $event['BlogPosts'][0];
			$elapsed = time() - strtotime($blogPost['Created']);
			if($elapsed < 604800)
			{
				$event['BlogPostThisWeek'] = TRUE;
			}
		}
		
		if($event['FundraisingGoalUSD'])
		{
			$event['FundraisingGoalRatio'] = $event['DonationTotalUSD'] / $event['FundraisingGoalUSD'];
		}
		
		
		if($event['ImageFilename'] != \Twestival\Services\EventService::DEFAULT_IMAGE_FILENAME)
		{
			$event['ImageNonDefault'] = TRUE;
		}
		
		return $this->renderMustacheHeaderFooter('Global/Admin/Event/View', array(
				'Event' => $event
		));
	}
	/**
	 * @method put
	 * @provides text/html
	 * @requireSiteAdmin
	 */
	function save($eventID)
	{
		$eventID = intval($eventID);
		
		$this->container['service.event']->saveSiteAdminFields(
				$eventID,
				$_POST['Active'] == 'true',
				$_POST['Name']);
	
		throw new \Twestival\RedirectException("/admin/event");
	}
}
?>