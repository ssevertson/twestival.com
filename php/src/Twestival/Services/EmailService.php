<?php namespace Twestival\Services;

class EmailService extends BaseService
{
	function send($to, $subject, $body)
	{
		$this->container['email.mailer']->send(
				$this->container['email.message']
						->setSubject($subject)
						->setBody($body)
						->setTo($to)
		);
	}
}
?>