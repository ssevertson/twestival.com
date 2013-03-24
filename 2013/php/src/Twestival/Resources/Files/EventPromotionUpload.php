<?php namespace Twestival\Resources\Files;

class EventPromotionUpload extends Upload
{
	function __construct($container, $pageName, $sectionName, $fields)
	{
		$promotions = $container['service.promotion'];
		$path = $container['baseDir'] . '/../' . $promotions->getImagePath($pageName, $sectionName) . '/';
		$uri = $promotions->getImageUri($pageName, $sectionName);
		
		parent::__construct($path, $uri, $fields);

		$this->setMaxFileSize(16 * 1024);
		$this->setMimeTypes('image/png, image/jpeg');
		
		$pages = $container['service.page'];
		$pageSection = $pages->getPageSection($pageName, $sectionName);
		
		$this->setExactImageWidth($pageSection['ImageWidth']);
		$this->setExactImageHeight($pageSection['ImageHeight']);
	}
}
?>