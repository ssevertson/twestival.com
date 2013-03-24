<?php namespace Twestival\Services;

class PageService extends BaseService
{
	function getPageContents()
	{
		$pages = $this->container['dao.pages'];
		return $pages->getPageContents();
	}
	function getPageContent($pageName, $contentName)
	{
		$pages = $this->container['dao.pages'];
		return $pages->getPageContent($pageName, $contentName);
	}
	function updatePageContent($pageName, $contentName, $html)
	{
		$pages = $this->container['dao.pages'];
		$pages->updatePageContent($pageName, $contentName, $html);
	}
	function getPageSections()
	{
		$pages = $this->container['dao.pages'];;
		return $pages->getPagePromotionSections();
	}
	function getPageSection($pageName, $sectionName)
	{
		$pages = $this->container['dao.pages'];;
		return $pages->getPagePromotionSection($pageName, $sectionName);
	}
}
?>