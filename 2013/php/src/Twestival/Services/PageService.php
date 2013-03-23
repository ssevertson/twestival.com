<?php namespace Twestival\Services;

use Twestival\DAOs\EventsDAO;
use Twestival\DAOs\PagesDAO;

class PageService extends BaseService
{
	function getPageContents()
	{
		$pages = new PagesDAO($this->container);
		return $pages->getPageContents();
	}
	function getPageContent($pageName, $contentName)
	{
		$pages = new PagesDAO($this->container);
		return $pages->getPageContent($pageName, $contentName);
	}
	function updatePageContent($pageName, $contentName, $html)
	{
		$pages = new PagesDAO($this->container);
		$pages->updatePageContent($pageName, $contentName, $html);
	}
}
?>