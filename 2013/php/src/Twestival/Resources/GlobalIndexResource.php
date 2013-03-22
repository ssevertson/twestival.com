<?php namespace Twestival\Resources;

use Twestival\Services\PromotionService;
/**
 * @namespace global
 * @uri /index
 */
class GlobalIndexResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html()
	{
		$promotions = new PromotionService($this->container);
		$primaryPromotions = $promotions->getForPageSection('HOME', 'PRIMARY', 4, FALSE);
		$secondaryPromotions = $promotions->getForPageSection('HOME', 'SECONDARY', 11, TRUE);
		
		return $this->renderMustacheHeaderFooter('GlobalIndex', array(
			'PrimaryPromotions' => $primaryPromotions,
			'SecondaryPromotionsRow1' => $this->toRow($secondaryPromotions, 0, 3),
			'SecondaryPromotionsRow2' => $this->toRow($secondaryPromotions, 4, 7),
			'SecondaryPromotionsRow3' => $this->toRow($secondaryPromotions, 8, 10),
		));
	}
	
	function toRow($array, $start, $stop)
	{
		$row = array();
		for($i = $start; $i <= $stop; $i++)
		{
			$item = $array[$i];
			if($i == $start)
			{
				$item['Position'] = 'left';
			}
			if($i == $stop)
			{
				$item['Position'] = 'right';
			}
			array_push($row, $item);
		}
		return $row;
	}
}
?>