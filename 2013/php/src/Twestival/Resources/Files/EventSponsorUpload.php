<?php namespace Twestival\Resources\Files;

class EventSponsorUpload extends Upload
{
	function __construct($container, $fields)
	{
		$eventCharities = $container['service.event.sponsors'];
		$path = $container['baseDir'] . '/../' . $eventCharities->getImagePath() . '/' . \Twestival\Services\EventSponsorService::IMAGE_FILENAME_PREFIX;
		$uri = $eventCharities->getImageUri();
		
		parent::__construct($path, $uri, $fields);

		$this->setMaxFileSize(64 * 1024);
		$this->setMimeTypes('image/png, image/jpeg');
		
		$this->setMaxImageWidth(180);
		$this->setMaxImageHeight(84);
	}
}
?>