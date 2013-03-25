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
		$rendered = $context->render($text);
		return $rendered ? date('j F Y', strtotime($rendered)) : 'TBD';
	}
	public function _to24HourTime($text, $context)
	{
		$rendered = $context->render($text);
		return $rendered ? date('H:i T', strtotime($rendered)) : 'TBD';
	}
	
	public function _toTitleCase($text, $context)
	{
		$rendered = $context->render($text);
		return ucwords(strtolower($rendered));
	}
	public function _toLowerCase($text, $context)
	{
		$rendered = $context->render($text);
		return strtolower($rendered);
	}
	public function _toUpperCase($text, $context)
	{
		$rendered = $context->render($text);
		return strtoupper($rendered);
	}
	public function _toLocation($text, $context)
	{
		$rendered = $context->render($text);
		return preg_replace('/, , /', ', ', $rendered);
	}
	
	public function _toUrlQuery($text, $context)
	{
		$rendered = $context->render($text);
		return urlencode($rendered);
	}
}
?>