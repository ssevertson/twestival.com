<?php namespace Twestival\Resources\Files;

class EventUpload extends Upload
{
	function __construct($container, $fields)
	{
		$events = $container['service.event'];
		$path = $container['baseDir'] . '/../' . $events->getImagePath() . '/' . \Twestival\Services\EventService::IMAGE_FILENAME_PREFIX;
		$uri = $events->getImageUri();
		
		parent::__construct($path, $uri, $fields);

		$this->setMaxFileSize(64 * 1024);
		$this->setMimeTypes('image/png, image/jpeg');
		
		$this->setExactImageWidth(170);
		$this->setExactImageHeight(126);
	}
}
?>