<?php namespace Twestival\Resources;

class BaseBlogResource extends BaseResource
{
	function renderMustacheHeaderFooter($template, $data = array())
	{
		if($data['Event'])
		{
			$event = &$data['Event'];
			if($event['FundraisingGoalUSD'])
			{
				$event['FundraisingGoalRatio'] = $event['DonationTotalUSD'] / $event['FundraisingGoalUSD'];
			}
			if($event['Sponsors'])
			{
				$event['SponsorRows'] = $this->toGrid($event['Sponsors'], 2);
			}
		}
		
		return parent::renderMustacheHeaderFooter($template, $data);
	}
}