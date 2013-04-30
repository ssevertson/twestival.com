<?php namespace Twestival\Resources;

/**
 * @namespace global
 * @uri /impact
 * @uri /impact/{level}
 */
class GlobalImpactResource extends BaseResource
{
	/**
	 * @method get
	 * @provides text/html
	 */
	function html($defaultLevel = 'CONTINENT')
	{
		$years = $this->container['service.year']->getYears();
		$defaultYear = $this->selectByField(TRUE, 'Active', $years);
		
		$currencies = $this->container['service.currency']->getCurrencies();
		$currencies = $this->addJSON($currencies);
		
		$defaultCurrency = NULL;
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$defaultISO4217Code = $this->getDefaultISO4217Code($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$defaultCurrency = $this->selectByField($defaultISO4217Code, 'ISO4217Code', $currencies);
		}
		
		if(!$defaultCurrency)
		{
			$defaultCurrency = $this->selectByField('USD', 'ISO4217Code', $currencies);
		}
		
		$defaultLevel = strtoupper($defaultLevel);
				
		switch($defaultLevel)
		{
			case 'CONTINENT':
			case 'COUNTRY':
			case 'CITY':
				break;
			default:
				$defaultLevel = 'CONTINENT';
				break;
		}
		
		return $this->renderMustacheHeaderFooter('Global/Impact', array(
				'Years' => $years,
				'DefaultYear' => $defaultYear,
				'Currencies' => $currencies,
				'DefaultCurrency' => $defaultCurrency,
				'DefaultLevel' => $defaultLevel
		));
	}
	
	private function addJSON(&$currencies)
	{
		foreach($currencies as &$currency)
		{
			$currency['JSON'] = json_encode($currency);
		}
		return $currencies;
	}
	private function getDefaultISO4217Code($acceptLanguage)
	{
		$locale = \Locale::acceptFromHttp($acceptLanguage);
		if(empty($locale))
		{
			return null;
		}
		
		$numberFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
		return $numberFormatter->getTextAttribute(\NumberFormatter::CURRENCY_CODE);
	}
}
?>