<?php namespace Twestival\Resources;

class BaseBlogResource extends BaseResource
{
	function renderMustacheHeaderFooter($template, $data = array())
	{
		if(isset($data['Event']))
		{
			$event = &$data['Event'];
			if(isset($event['FundraisingGoalUSD']) && $event['FundraisingGoalUSD'])
			{
				$event['FundraisingGoalRatio'] = $event['DonationTotalUSD'] / $event['FundraisingGoalUSD'];
			}
			if(isset($event['Sponsors']))
			{
				$event['SponsorRows'] = $this->toGrid($event['Sponsors'], 2);
			}
		}
		
		return parent::renderMustacheHeaderFooter($template, $data);
	}
}