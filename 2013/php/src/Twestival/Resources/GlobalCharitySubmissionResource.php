<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /nposubmission
 */
class GlobalCharitySubmissionResource extends BaseResource
{
	/**
	 * @method get
	 */
	function redirect()
	{
		throw new \Twestival\RedirectException('http://goo.gl/s92On');
	}
}
?>