<?php namespace Twestival\Resources;

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
		$promotions = $this->container['service.promotion'];
		$primaryPromotions = $promotions->getList('HOME', 'PRIMARY', FALSE);
		$secondaryPromotions = $promotions->getList('HOME', 'SECONDARY', TRUE);
		
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