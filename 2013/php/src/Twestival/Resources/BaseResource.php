<?php namespace Twestival\Resources;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class BaseResource extends \Tonic\Resource
{
	protected function getRelativeUri($path)
	{
		return $this->app->baseUri . $path;
	}
	
	protected function renderMustache($template, $data)
	{
		$mustache = new Mustache_Engine(array(
			'loader' => new Mustache_Loader_FilesystemLoader($this->app->baseDir . '/src/Twestival/Views'),
			'helpers' => array(
				'format_number' => new Helpers\NumberFormatters()
			)
		));
		
		return $mustache->loadTemplate($template)->render($data);
	}
}
?>