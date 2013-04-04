<?php namespace Twestival\Resources\Files;

class EventSponsorUpload extends Upload
{
	function __construct($container, $fields)
	{
		$eventSponsors = $container['service.event.sponsor'];
		$path = $container['baseDir'] . '/../' . $eventSponsors->getImagePath() . '/' . \Twestival\Services\EventSponsorService::IMAGE_FILENAME_PREFIX;
		$uri = $eventSponsors->getImageUri();
		
		parent::__construct($path, $uri, $fields);

		$this->setMaxFileSize(64 * 1024);
		$this->setMimeTypes('image/png, image/jpeg');
		
		$this->setMaxImageWidth(120);
		$this->setMaxImageHeight(120);
	}
}
?>