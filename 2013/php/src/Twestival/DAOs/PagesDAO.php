<?php namespace Twestival\DAOs;

class PagesDAO extends BaseDAO
{
	function getPagePromotionSections()
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				SitePage.SitePageID,
				SitePage.Name AS PageName,
				SitePagePromotionSection.PromotionSectionID,
				SitePagePromotionSection.Name AS SectionName
			FROM
				SitePage
				INNER JOIN SitePagePromotionSection
					ON SitePage.SitePageID = SitePagePromotionSection.SitePageID
			ORDER BY
				SitePage.Name,
				SitePagePromotionSection.Name;
		');
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_OBJ);
	}
	function getPageContents()
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				SitePage.SitePageID,
				SitePage.Name AS PageName,
				SiteContent.SiteContentID,
				SiteContent.Name AS ContentName
			FROM
				SitePage
				INNER JOIN SiteContent
					ON SitePage.SitePageID = SiteContent.SitePageID
			ORDER BY
				SitePage.Name,
				SiteContent.Name;
		');
		$query->execute();
		return $query->fetchAll(\PDO::FETCH_OBJ);
	}
	function getPageContent($pageName, $contentName)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				SitePage.SitePageID,
				SitePage.Name AS PageName,
				SiteContent.SiteContentID,
				SiteContent.Name AS ContentName,
				SiteContent.HTML
			FROM
				SitePage
				INNER JOIN SiteContent
					ON SitePage.SitePageID = SiteContent.SitePageID
			WHERE
				SitePage.Name = UPPER(?)
				AND SiteContent.NAME = UPPER(?);
		');
		$query->bindValue(1, $pageName, \PDO::PARAM_STR);
		$query->bindValue(2, $contentName, \PDO::PARAM_STR);
		$query->execute();
		return $query->fetch(\PDO::FETCH_OBJ);
	}
	function updatePageContent($pageName, $contentName, $html)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				SitePage, SiteContent
			SET
				SiteContent.HTML = ?
			WHERE
				SitePage.SitePageID = SiteContent.SitePageID
				AND SitePage.Name = UPPER(?)
				AND SiteContent.NAME = UPPER(?);
		');
		$query->bindValue(1, $html, \PDO::PARAM_STR);
		$query->bindValue(2, $pageName, \PDO::PARAM_STR);
		$query->bindValue(3, $contentName, \PDO::PARAM_STR);
		$query->execute();
		return $query->rowCount();
	}
}
?>