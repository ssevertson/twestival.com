<?php namespace Twestival\Services;

class CurrencyService extends BaseService
{
	function updateExchangeRates()
	{
		$currencies = $this->container['dao.currencies'];
		
		$rates = $this->getExchangeRates();
		
		foreach($this->getCurrencies() as $currency)
		{
			$code = $currency['ISO4217Code'];
			$rate = $rates[$code];
			$currencies->updateExchangeRate($code, $rate);
		}
	}
	function getCurrencies()
	{
		$currencies = $this->container['dao.currencies'];
		$results = $currencies->items();
		return $this->toNumerics($results);
	}
	private function getExchangeRates()
	{
		$client = $this->container['openexchange.client'];
		$request = $client->get('latest.json?app_id={app_id}');
		$response = $request->send();
		$data = $response->json();
		return $data['rates'];
	}
	private function toNumerics(&$results)
	{
		foreach($results as &$result)
		{
			$result['ExchangeRate'] = floatval($result['ExchangeRate']);
		}
		return $results;
	}
}
?>