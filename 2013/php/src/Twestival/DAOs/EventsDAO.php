<?php namespace Twestival\DAOs;

class EventsDAO extends BaseDAO
{
	function countEventLocationsByType($locationType)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				COUNT(DISTINCT Location.LocationID)
			FROM
				Year
				INNER JOIN Event
					ON Year.Year = Event.Year
				INNER JOIN EventLocation
					ON Event.EventId = EventLocation.EventId
				INNER JOIN Location
					ON EventLocation.LocationID = Location.LocationID
			WHERE
				Year.Active = TRUE
				AND Location.Type = ?
		');
		$query->bindValue(1, $locationType, \PDO::PARAM_STR);
		$query->execute();
		return intval($query->fetchColumn());
	}
	
	function sumEventDonationTotalUSD()
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				SUM(Event.DonationTotalUSD)
			FROM
				Year
				INNER JOIN Event
					ON Year.Year = Event.Year
			WHERE
				Year.Active = TRUE
		');
		$query->execute();
		return floatval($query->fetchColumn());
	}
	
	function getPromotionsForPageSection($pageName, $sectionName, $limit)
	{
		$conn = $this->container['connection'];
		$query = $conn->prepare('
			SELECT
				Event.*,
				EventPromotion.Name AS PromotionName,
				EventPromotion.ImageFilename AS PromotionImageFilename,
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
		return $query->fetchAll(\PDO::FETCH_OBJ);
	}
}
?>