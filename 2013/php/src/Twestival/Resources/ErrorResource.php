<?php namespace Twestival\Resources;

/**
 * @uri /error
 */
class ErrorResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		$code = $_GET['code'];
		if(is_numeric($code))
		{
			header(':', true, intval($code));
		}
		return $this->renderMustache('Error');
	}
}
?>