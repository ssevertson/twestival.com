<?php namespace Twestival\Resources\Helpers;

class Formatters extends BaseHelper
{
	const US_LOCALE = 'en_US.UTF-8';

	public function _toUSD($text, $context)
	{
		$rendered = $context->render($text);
		if(!$rendered)
		{
			$rendered = '0';
		}
		setlocale(LC_ALL, self::US_LOCALE);
		return money_format('%.2n', floatval($context->render($text)));
	}
	
	public function _toUSDollars($text, $context)
	{
		$rendered = $context->render($text);
		if(!$rendered)
		{
			$rendered = '0';
		}
		setlocale(LC_ALL, self::US_LOCALE);
		return money_format('%.0n', floatval($rendered));
	}
	
	public function _toUSMillions($text, $context)
	{
		$rendered = $context->render($text);
		if(!$rendered)
		{
			$rendered = '0';
		}
		setlocale(LC_ALL, self::US_LOCALE);
		return money_format('%.2n', floatval($rendered) / 1000000);
	}
	
	public function _toUSInteger($text, $context)
	{
		$rendered = $context->render($text);
		if(!$rendered)
		{
			$rendered = '0';
		}
		
		setlocale(LC_ALL, self::US_LOCALE);
		$locale = localeconv();
		return number_format(
				intval($rendered),
				0,
				$locale['decimal_point'],
				$locale['thousands_sep']);
	}
	
	public function _toEuropeanDate($text, $context)
	{
		$rendered = $context->render($text);
		return $rendered ? date('j F Y', strtotime($rendered)) : 'TBD';
	}
	public function _toLongEuropeanDate($text, $context)
	{
		$rendered = $context->render($text);
		return $rendered ? date('l, j F Y', strtotime($rendered)) : 'Coming Soon';
	}
	public function _removeSeconds($text, $context)
	{
		$rendered = $context->render($text);
		$value = preg_replace('/:[0-9][0-9]$/', '', $rendered);
		return $value;
	}
	public function _to24HourTimeWithZone($text, $context)
	{
		$rendered = $context->render($text);
		return $rendered ? date('H:i T', strtotime($rendered)) : 'TBD';
	}
	public function _toHumanElapsedTime($text, $context)
	{
		$rendered = $context->render($text);
		if(!$rendered)
		{
			return 'TBD';
		}
		return $this->formatHumanElapsedTime(abs(time() - strtotime($rendered)));
	}
	private function formatHumanElapsedTime($elapsed)
	{
		if($elapsed == 0)
		{
			return 'Now';
		}
		
		$units = array (
				31536000 => 'year',
				2592000 => 'month',
				604800 => 'week',
				86400 => 'day',
				3600 => 'hour',
				60 => 'minute',
				1 => 'second'
		);
		
		setlocale(LC_ALL, self::US_LOCALE);
		$locale = localeconv();
		
		$absElapsed = abs($elapsed);
		foreach ($units as $seconds => $unit)
		{
			if ($absElapsed < $seconds) continue;
			
			$decimals = ($seconds >= 2592000);
			$rawCount = $absElapsed / $seconds;
			$formattedCount = number_format(
					$rawCount,
					$decimals,
					$locale['decimal_point'],
					$locale['thousands_sep']);
			$formattedCount = preg_replace('/\.0/', '', $formattedCount);
			
			return $formattedCount . ' '. $unit . (($rawCount > 1) ? 's' : '') . ($elapsed > 0 ? ' ago' : ' to go');
		}
	}
	public function _toPercentage($text, $context)
	{
		$rendered = $context->render($text);
		if(!$rendered)
		{
			$rendered = '0';
		}
		return floor(floatval($rendered) * 100) . '%';
	}
	public function _toPercentageMax($text, $context)
	{
		$rendered = $context->render($text);
		if(!$rendered)
		{
			$rendered = '0,100';
		}
		$parts = preg_split('/\s,\s/', $rendered, 2);
		$value = floatval($parts[0]) * 100;
		$max = (count($parts) == 2) ? floatval($parts[1]) : 100;
		if($value > $max)
		{
			$value = $max;
		}
		return floor($value) . '%';
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
		$value = preg_replace('/, , /', ', ', $rendered);
		return ($value != ', ') ? $value : 'Unknown';
	}
	
	public function _toUrlQuery($text, $context)
	{
		$rendered = $context->render($text);
		return urlencode($rendered);
	}
}
?>