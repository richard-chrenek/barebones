<?php

namespace Barebones\Helpers;

use DateTime;

class DateHelper
{
    /**
     * @param $date
     * @param DateTime $datetime
     * @return bool|null True/false, if valid dateTime was provided, or null in case of DateTime creation error
     */
    public static function isDate($date, DateTime $datetime)
    {
        $datetime = $datetime->getTimestamp();
        try {
            $today = (new DateTime($date))->setTime(0, 0)->getTimestamp();
        } catch (\Exception $e) {
            return null;
        }
        return ($datetime >= $today && $datetime < ($today + 24 * 60 * 60));
    }

    public static function isToday(DateTime $datetime)
    {
        return self::isDate('now', $datetime);
    }

    public static function isYesterday(DateTime $datetime)
    {
        return self::isDate('yesterday', $datetime);
    }

    /**
     * @param string $date
     * @return bool
     */
    public static function isValidDate($date)
    {
        if (strtotime($date) === false) {
            return false;
        }
        list($year, $month, $day) = explode('-', $date);
        return checkdate($month, $day, $year);
    }
}
