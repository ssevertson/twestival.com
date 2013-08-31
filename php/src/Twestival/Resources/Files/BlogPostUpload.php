<?php namespace Twestival\Resources\Files;

class BlogPostUpload extends Upload
{
	function __construct($container, $fields)
	{
		$posts = $container['service.blog.post'];
		$path = $container['baseDir'] . '/../' . $posts->getImagePath() . '/' . \Twestival\Services\BlogPostService::IMAGE_FILENAME_PREFIX;
		$uri = $posts->getImageUri();
		
		parent::__construct($path, $uri, $fields);

		$this->setMaxFileSize(128 * 1024);
		$this->setMaxImageWidth(590);
		$this->setMaxImageHeight(590);
		$this->setMimeTypes('image/png, image/jpeg');
	}
}
?>