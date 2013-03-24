<?php namespace Twestival\DAOs;

class EventPromotionsDAO extends BaseDAO
{
	function getMaxEvents($pageName, $sectionName)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				SitePagePromotionSection.MaxEvents
			FROM
				SitePage
				INNER JOIN SitePagePromotionSection
					ON SitePage.SitePageID = SitePagePromotionSection.SitePageID
			WHERE
				SitePage.Name = ?
				AND SitePagePromotionSection.Name = ?;
		');
		$query->bindValue(1, $pageName, \PDO::PARAM_STR);
		$query->bindValue(2, $sectionName, \PDO::PARAM_STR);
		
		$query->execute();
		return intval($query->fetchColumn());
	}

	function items($pageName, $sectionName, $limit)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				Event.Date AS EventDate,
				EventPromotion.Name AS Name,
				EventPromotion.ImageFilename AS ImageFilename,
				EventPromotion.Sequence,
				Blog.Subdomain
			FROM
				SitePage
				INNER JOIN SitePagePromotionSection
					ON SitePage.SitePageID = SitePagePromotionSection.SitePageID
				INNER JOIN EventPromotion
					ON SitePagePromotionSection.PromotionSectionID = EventPromotion.PromotionSectionID
				INNER JOIN Event
					ON EventPromotion.EventID = Event.EventID
				INNER JOIN Blog
					ON Event.BlogID = Blog.BlogID
			WHERE
				SitePage.Name = ?
				AND SitePagePromotionSection.Name = ?
			ORDER BY
				EventPromotion.Sequence
			LIMIT ?;
		');
		$query->bindValue(1, $pageName, \PDO::PARAM_STR);
		$query->bindValue(2, $sectionName, \PDO::PARAM_STR);
		$query->bindValue(3, $limit, \PDO::PARAM_INT);

		$query->execute();
		return $query->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	function get($pageName, $sectionName, $sequence)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				EventPromotion.EventID,
				EventPromotion.Name AS Name,
				EventPromotion.ImageFilename AS ImageFilename
			FROM
				SitePage
				INNER JOIN SitePagePromotionSection
					ON SitePage.SitePageID = SitePagePromotionSection.SitePageID
				INNER JOIN EventPromotion
					ON SitePagePromotionSection.PromotionSectionID = EventPromotion.PromotionSectionID
			WHERE
				SitePage.Name = ?
				AND SitePagePromotionSection.Name = ?
				AND EventPromotion.Sequence = ?;
		');
		$query->bindValue(1, $pageName, \PDO::PARAM_STR);
		$query->bindValue(2, $sectionName, \PDO::PARAM_STR);
		$query->bindValue(3, $sequence, \PDO::PARAM_INT);
	
		$query->execute();
		return $query->fetch(\PDO::FETCH_ASSOC);
	}
	
	function create($pageName, $sectionName, $sequence, $eventID, $name, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			INSERT INTO EventPromotion (EventID, PromotionSectionID, Name, ImageFilename, Sequence)
			SELECT
				?,
				SitePagePromotionSection.PromotionSectionID,
				?,
				?,
				?
			FROM
				SitePage
				INNER JOIN SitePagePromotionSection
					ON SitePage.SitePageID = SitePagePromotionSection.SitePageID
			WHERE
				SitePage.Name = ?
				AND SitePagePromotionSection.Name = ?;
		');
		$query->bindValue(1, $eventID, \PDO::PARAM_INT);
		$query->bindValue(2, $name, \PDO::PARAM_STR);
		$query->bindValue(3, $imageFilename, \PDO::PARAM_STR);
		$query->bindValue(4, $sequence, \PDO::PARAM_INT);
		$query->bindValue(5, $pageName, \PDO::PARAM_STR);
		$query->bindValue(6, $sectionName, \PDO::PARAM_STR);
	
		return $query->execute();
		return $query->rowCount();
	}
	function update($pageName, $sectionName, $sequence, $eventID, $name, $imageFilename)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				EventPromotion,
				SitePagePromotionSection,
				SitePage
			SET
				EventPromotion.EventID = ?,
				EventPromotion.Name = ?,
				EventPromotion.ImageFilename = ?
			WHERE
				EventPromotion.PromotionSectionID = SitePagePromotionSection.PromotionSectionID
				AND SitePagePromotionSection.SitePageID = SitePage.SitePageID
				AND SitePage.Name = ?
				AND SitePagePromotionSection.Name = ?
				AND EventPromotion.Sequence = ?;
		');
		$query->bindValue(1, $eventID, \PDO::PARAM_INT);
		$query->bindValue(2, $name, \PDO::PARAM_STR);
		$query->bindValue(3, $imageFilename, \PDO::PARAM_STR);
		$query->bindValue(4, $pageName, \PDO::PARAM_STR);
		$query->bindValue(5, $sectionName, \PDO::PARAM_STR);
		$query->bindValue(6, $sequence, \PDO::PARAM_INT);
	
		return $query->execute();
		return $query->rowCount();
	}

	function delete($pageName, $sectionName, $sequence)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			DELETE FROM
				EventPromotion
			USING
				EventPromotion,
				SitePagePromotionSection,
				SitePage
			WHERE
				EventPromotion.PromotionSectionID = SitePagePromotionSection.PromotionSectionID
				AND SitePagePromotionSection.SitePageID = SitePage.SitePageID
				AND SitePage.Name = ?
				AND SitePagePromotionSection.Name = ?
				AND EventPromotion.Sequence = ?;
		');
		$query->bindValue(1, $pageName, \PDO::PARAM_STR);
		$query->bindValue(2, $sectionName, \PDO::PARAM_STR);
		$query->bindValue(3, $sequence, \PDO::PARAM_INT);
	
		return $query->execute();
		return $query->rowCount();
	}
	
	function updateSequence($pageName, $sectionName, $sequence, $newSequence)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			UPDATE
				EventPromotion,
				SitePagePromotionSection,
				SitePage
			SET
				EventPromotion.Sequence = ?
			WHERE
				EventPromotion.PromotionSectionID = SitePagePromotionSection.PromotionSectionID
				AND SitePagePromotionSection.SitePageID = SitePage.SitePageID
				AND SitePage.Name = ?
				AND SitePagePromotionSection.Name = ?
				AND EventPromotion.Sequence = ?;
		');
		$query->bindValue(1, $newSequence, \PDO::PARAM_INT);
		$query->bindValue(2, $pageName, \PDO::PARAM_STR);
		$query->bindValue(3, $sectionName, \PDO::PARAM_STR);
		$query->bindValue(4, $sequence, \PDO::PARAM_INT);
	
		$query->execute();
		return $query->rowCount();
	}
}
?>