<?php namespace Twestival\Resources\Files;

class EventCharityUpload extends Upload
{
	function __construct($container, $fields)
	{
		$eventSponsors = $container['service.event.charity'];
		$path = $container['baseDir'] . '/../' . $eventSponsors->getImagePath() . '/' . \Twestival\Services\EventCharityService::IMAGE_FILENAME_PREFIX;
		$uri = $eventSponsors->getImageUri();
		
		parent::__construct($path, $uri, $fields);

		$this->setMaxFileSize(64 * 1024);
		$this->setMimeTypes('image/png, image/jpeg');
		
		$this->setMaxImageWidth(180);
		$this->setMaxImageHeight(84);
	}
}
?>