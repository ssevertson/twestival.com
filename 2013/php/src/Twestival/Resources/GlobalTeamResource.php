<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /team
 */
class GlobalTeamResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		return $this->renderMustacheHeaderFooter('Global/Team');
	}
}
?>