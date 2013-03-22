<?php namespace Twestival\Resources\Helpers;

class Formatters extends BaseHelper
{
	const US_LOCALE = 'en_US.UTF-8';

	public function _toUSD($text, $context)
	{
		setlocale(LC_ALL, self::US_LOCALE);
		return money_format('%.2n', floatval($context->render($text)));
	}
	
	public function _toUSDollars($text, $context)
	{
		setlocale(LC_ALL, self::US_LOCALE);
		return money_format('%.0n', floatval($context->render($text)));
	}
	
	public function _toUSInteger($text, $context)
	{
		setlocale(LC_ALL, self::US_LOCALE);
		$locale = localeconv();
		return number_format(
			intval($context->render($text)),
			0,
			$locale['decimal_point'],
			$locale['thousands_sep']);
	}
	
	public function _toEuropeanDate($text, $context)
	{
		$dateText = $context->render($text);
		return date('j F Y', strtotime($dateText));
	}
}
?>