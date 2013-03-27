<?php namespace Twestival\Resources;

class BaseBlogResource extends BaseResource
{
	function renderMustacheHeaderFooter($template, $data = array())
	{
		$data['Event'] = $this->container['service.event']->getEvent($data['EventID']);
		if($data['Event']['FundraisingGoalUSD'])
		{
			$data['Event']['FundraisingGoalRatio'] = $data['Event']['DonationTotalUSD'] / $data['Event']['FundraisingGoalUSD'];
		}
		
		$data['EventCharities'] = $this->container['service.event.charity']->getCharities($data['EventID']);
		
		$data['EventTeamMembers'] = $this->container['service.event.teamMember']->getTeamMembers($data['EventID']);
		
		$eventSponsors = $this->container['service.event.sponsor']->getSponsors($data['EventID']);
		$data['EventSponsorRows'] = $this->toGrid($eventSponsors, 2);
		
		return parent::renderMustacheHeaderFooter($template, $data);
	}
}