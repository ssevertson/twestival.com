<?php namespace Twestival\Services;

class ThankYouService extends BaseService
{
	function getMessage($type)
	{
		switch($type)
		{
			case 'register':
				return 'Thank you for registering to host a Twestival! We aim to complete applications within 24-72 hours. If you do not hear back from us, or have further questions, please contact <a href="mailto:registration@twestival.com">registration@twestival.com</a>, or tweet us @twestival!';
			case 'volunteer':
				return 'Thank you for your interest in volunteering! Your information will be shared with your local organizing team, which should be in touch with you shortly. If there is no event in your city yet, your information will be passed on to the organizing team when one is formed. If you don\'t hear back from someone, please email us at <a href="mailto:volunteer@twestival.com">volunteer@twestival.com</a>, or tweet us @twestival!';
			case 'sponsor':
				return 'Thank you for your interest in sponsoring Twestival! Your information will be shared with your local organizing team, which should be in touch with you shortly. If there is no event in your city yet, your information will be passed on to the organizing team when one is formed. If you have any questions, please contact <a href="mailto:sponsorship@twestival.com">sponsorship@twestival.com</a>, or tweet us @twestival!';
			default:
				return 'Thank you for your submission!';
		}
	}
}
?>