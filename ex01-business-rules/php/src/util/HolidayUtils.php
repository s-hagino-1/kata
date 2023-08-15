<?php

namespace Kata\Ex01\Util;

use DateTime;
use DateTimeZone;

class HolidayUtils
{
    private const DTSTART_PTN = "/^DTSTART;VALUE=DATE:(\\d{8})$/";
    private const SUMMARY_PTN = "/^SUMMARY:(.+)$/";
    private const HOLIDAYS_OF_WEEK = [5, 6]; // DayOfWeek::SATURDAY, DayOfWeek::SUNDAY

    private static array $holidays = [];

    private static function isCacheAvailable(string $cacheFile): bool
    {
        $now = new DateTime("now");
        $cacheLastModified = file_exists($cacheFile) ? filemtime($cacheFile) : 0;

        return $cacheLastModified > 0 &&
            (new DateTime("@$cacheLastModified"))
            ->setTimezone(new DateTimeZone(date_default_timezone_get()))
            ->format('Y') == $now->format('Y');
    }

    private static function createOutputStream(string $file)
    {
        if (self::isCacheAvailable($file)) {
            return fopen($file, "w"); // Dummy writable stream
        } else {
            if (!file_exists(dirname($file))) {
                mkdir(dirname($file), 0777, true);
            }
            return fopen($file, "w");
        }
    }

    public static function initializeHolidays()
    {
        try {
            $cacheFile = "target/basic.ics";
            $url = file_exists($cacheFile)
                ? $cacheFile
                : "https://calendar.google.com/calendar/ical/ja.japanese%23holiday%40group.v.calendar.google.com/public/basic.ics";

            $in = fopen($url, "r");
            $out = self::createOutputStream($cacheFile);

            $name = null;
            $date = null;

            $ptn = "Ymd";
            while (($line = fgets($in)) !== false) {
                if ($line === "BEGIN:VEVENT\n") {
                    $name = null;
                    $date = null;
                } elseif (preg_match(self::DTSTART_PTN, $line, $dtstartMatches)) {
                    $date = DateTime::createFromFormat($ptn, $dtstartMatches[1]);
                } elseif (preg_match(self::SUMMARY_PTN, $line, $summaryMatches)) {
                    $name = $summaryMatches[1];
                } elseif ($line === "END:VEVENT\n") {
                    if ($date && $name) {
                        self::$holidays[$date->format($ptn)] = $name;
                    }
                }
                fwrite($out, $line);
            }

            fclose($in);
            fclose($out);
        } catch (\Throwable $e) {
            throw new \RuntimeException($e);
        }
    }

    public static function isHoliday($date): bool
    {
        if ($date instanceof DateTime) {
            $dayOfWeek = intval($date->format("N"));
            $formattedDate = $date->format("Ymd");
            return in_array($dayOfWeek, self::HOLIDAYS_OF_WEEK) || array_key_exists($formattedDate, self::$holidays);
        }
        return false;
    }

    public static function printHolidays()
    {
        uksort(self::$holidays, function ($a, $b) {
            return strcmp($a, $b);
        });
        foreach (self::$holidays as $date => $name) {
            echo $date . ": " . $name . "\n";
        }
    }
}
