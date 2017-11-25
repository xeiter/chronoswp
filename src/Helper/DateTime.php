<?php
namespace ChronosWP\Helper;

class DateTime {
	/**
	 * Convert seconds to years/days/hours etc.
	 *
	 * @param int $time
	 * @return array|bool
	 * @static
	 * @access public
	 */
	public static function secondsToTime( $time ) {
		if(is_numeric($time)){
			$value = array(
				"years" => 0, "days" => 0, "hours" => 0,
				"minutes" => 0, "seconds" => 0,
			);

			if( $time >= 31556926 ) {
				$value["years"] = floor( $time / 31556926 );
				$time = ( $time % 31556926 );
			}

			if ( $time >= 86400 ) {
				$value["days"] = floor( $time / 86400 );
				$time = ( $time % 86400 );
			}

			if( $time >= 3600 ) {
				$value["hours"] = floor( $time / 3600 );
				$time = ( $time % 3600 );
			}

			if ( $time >= 60 ) {
				$value["minutes"] = floor( $time / 60 );
				$time = ( $time % 60 );
			}

			$value["seconds"] = floor($time) ;

			return (array) $value;
		} else {
			return (bool) FALSE;
		}
	}

	/**
	 * Returns the timezone string for a site, even if it's set to a UTC offset
	 *
	 * Taken from https://www.skyverge.com/blog/down-the-rabbit-hole-wordpress-and-timezones/
	 *
	 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
	 *
	 * @return string valid PHP timezone string
	 */
	public static function getTimezoneString() {
	    // if site timezone string exists, return it
	    if ($timezone = get_option('timezone_string')) {
	        return $timezone;
	    }

	    // get UTC offset, if it isn't set then return UTC
	    if (0 === ($utcOffset = get_option('gmt_offset', 0))) {
	        return 'UTC';
	    }

	    // adjust UTC offset from hours to seconds
	    $utcOffset *= 3600;

	    // attempt to guess the timezone string from the UTC offset
	    if ($timezone = timezone_name_from_abbr('', $utcOffset, 0)) {
	        return $timezone;
	    }

	    // last try, guess timezone string manually
	    $is_dst = date('I');

	    foreach (timezone_abbreviations_list() as $abbr) {
	        foreach ($abbr as $city) {
	            if ($city['dst'] == $is_dst && $city['offset'] == $utcOffset) {
	                return $city['timezone_id'];
	            }
	        }
	    }

	    // fallback to UTC
	    return 'UTC';
	}

	/**
	 * Get DateTimeZone object for current site
	 *
	 * @param string $timezoneStr
	 *
	 * @return DateTimeZone
	 */
	public static function getTimezone($timezoneStr = '') {
	    if (empty($timezoneStr)) {
		    $timezoneStr = self::getTimezoneString();
	    }
	    return new \DateTimeZone($timezoneStr);
	}

	/**
	 * Get DateTime object for current time
	 * @param DateTimeZone $timezone
	 * @return DateTime
	 */
	public static function getCurrentDatetime(\DateTimeZone $timezone = null) {
	    if (is_null($timezone)) {
	        $timezone = self::getTimezone();
	    }
	    return new \DateTime('now', $timezone);
	}

	/**
	 * Get DateTime object for specified date/time
	 * @param string $datetime
	 * @param DateTimeZone $timezone
	 * @return \DateTime
	 */
	public static function getDatetime($datetime = '', \DateTimeZone $timezone = null) {
	    if (empty($datetime)) {
	        return self::getCurrentDatetime($timezone);
	    }

	    if (is_int($datetime)) {
	        $datetime = '@'.$datetime;
	    }

	    if (is_null($timezone)) {
	        $timezone = self::getTimezone();
	    }
	    return new \DateTime($datetime, $timezone);
	}
}
