<?php

namespace CSA\Common;

use DateTime;

/**
 * The TimeFormat class is common utility class to transform a time value like 12:02:03 into
 * a timestamp based on seconds after the current day 00:00:00. It also supports times after 24:*, which
 * are especially used in trips scheduled after midnight.
 *
 * @package CSA\Common
 */
class TimeFormat
{
    /**
     * Converts an time string formatted like HH:mm:ss into an second-based timestamp.
     *
     * @param $inputTime The input time string
     * @return int The timestamp based on seconds
     * @throws \Exception When the time conversion fails
     */
    public static function getTimestamp($inputTime)
    {
        $items = array_map('intval', explode(':', $inputTime));

        $dateTime = new DateTime();
        $dateTime->setTime(isset($items[0]) ? $items[0] : 0, isset($items[1]) ? $items[1] : 0, isset($items[2]) ? $items[2] : 0);

        return $dateTime->getTimestamp() - strtotime('TODAY');
    }

    /**
     * Converts a second-based timestamp into a string formatted like HH:mm:ss.
     *
     * @param $timestamp The current timestamp to transform
     * @param string $timeFormat The desired PHP time format
     * @return string The formatted time as string
     * @throws \Exception When the time conversion fails
     */
    public static function getString($timestamp, $timeFormat = 'H:i:s')
    {
        $dateTime = new DateTime();
        $dateTime->setTimestamp($timestamp + strtotime('TODAY'));

        return $dateTime->format($timeFormat);
    }
}