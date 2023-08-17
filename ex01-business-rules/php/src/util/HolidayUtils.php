<?php

namespace Kata\Ex01\Util;

use DateTime;
use DateTimeZone;

class HolidayUtils
{
    private const HOLIDAYS_OF_WEEK = [5, 6]; // DayOfWeek::SATURDAY, DayOfWeek::SUNDAY

    private array $holidays = [];

    private function isCacheAvailable(string $cacheFile): bool
    {
        $now = new DateTime("now");
        $cacheLastModified = file_exists($cacheFile) ? filemtime($cacheFile) : 0;

        return $cacheLastModified > 0 &&
            (new DateTime("@$cacheLastModified"))
            ->setTimezone(new DateTimeZone(date_default_timezone_get()))
            ->format('Y') == $now->format('Y');
    }

    private function createOutputStream(string $file)
    {
        if ($this->isCacheAvailable($file)) {
            return null;
        } else {
            if (!file_exists(dirname($file))) {
                mkdir(dirname($file), 0777, true);
            }
            return fopen($file, "w");
        }
    }

    public function __construct()
    {
        try {
            $cacheFile = "target/basic.ics";
            $url = file_exists($cacheFile)
                ? $cacheFile
                : "https://calendar.google.com/calendar/ical/ja.japanese%23holiday%40group.v.calendar.google.com/public/basic.ics";

            $in = fopen($url, "r");
            $out = $this->createOutputStream($cacheFile);

            $name = null;
            $date = null;

            $ptn = "Ymd";

            while (($line = fgets($in)) !== false) {
                if (preg_match("/^BEGIN:VEVENT/", $line)) {
                    $name = null;
                    $date = null;
                } elseif (preg_match("/^DTSTART;VALUE=DATE:(\d+)/", $line, $dtstartMatches)) {
                    $date = DateTime::createFromFormat($ptn, $dtstartMatches[1]);
                } elseif (preg_match("/^SUMMARY:(.+)\\n$/", $line, $summaryMatches)) {
                    $name = $summaryMatches[1];
                } elseif (preg_match("/^END:VEVENT/", $line)) {
                    if ($date && $name) {
                        $this->holidays[$date->format($ptn)] = $name;
                    }
                }
                if ($out !== null) {
                    fwrite($out, $line);
                }
            }

            fclose($in);
            if ($out !== null) {
                fclose($out);
            }
        } catch (\Throwable $e) {
            throw new \RuntimeException($e);
        }
    }

    public function isHoliday(DateTime $date): bool
    {
        $dayOfWeek = intval($date->format("N"));
        $formattedDate = $date->format("Ymd");
        return in_array($dayOfWeek, self::HOLIDAYS_OF_WEEK) || array_key_exists($formattedDate, $this->holidays);
    }

    public function printHolidays()
    {
        uksort($this->holidays, function ($a, $b) {
            return strcmp($a, $b);
        });
        foreach ($this->holidays as $date => $name) {
            echo $date . ": " . $name . "\n";
        }
    }
}
