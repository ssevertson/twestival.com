<?php namespace Twestival\Services;

class ThankYouService extends BaseService
{
	function getMessage($type)
	{
		switch($type)
		{
			case 'register':
				return 'Thank you for registering to host a Twestival! We aim to complete applications within 24-72 hours. If you do not hear back from us, or have further questions, please contact <a href="mailto:registration@twestival.com">registration@twestival.com</a>.';
			default:
				return 'Thank you for your submission!';
		}
	}
}
?>