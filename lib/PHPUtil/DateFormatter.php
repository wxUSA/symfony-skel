<?php

namespace weatherUSA\PHPUtil;

use \DateTime;
use \DateTimeZone;

class DateFormatter
{
	public static function format($format, $ts, $tz)
	{
		$date = new DateTime("@$ts");
		try {
			$date->setTimezone(new DateTimeZone($tz));
		}
		catch(Exception $e) {
			// Fallback to php.ini default TZ
			$date->setTimezone(new DateTimeZone(date_default_timezone_get()));
		}
		return $date->format($format);
	}
}
