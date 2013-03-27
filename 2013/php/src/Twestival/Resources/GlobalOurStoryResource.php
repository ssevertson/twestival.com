<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /our_story
 */
class GlobalOurStoryResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/OurStory', array(
			'RunningStats' => $this->container['service.common']->getRunningSummaryStats()
		));
	}
}
?>