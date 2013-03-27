<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /volunteer
 */
class GlobalVolunteerResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Volunteer');
	}
}
?>