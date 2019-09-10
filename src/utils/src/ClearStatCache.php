<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace Hyperf\Utils;

class ClearStatCache
{
    /**
     * Interval at which to clear fileystem stat cache. Values below 1 indicate
     * the stat cache should ALWAYS be cleared. Otherwise, the value is the number
     * of seconds between clear operations.
     *
     * @var int
     */
    private static $interval = 1;

    /**
     * When the filesystem stat cache was last cleared.
     *
     * @var int
     */
    private static $lastCleared;

    public static function clear(?string $filename = null): void
    {
        $now = time();
        if (1 > self::$interval
            || self::$lastCleared
            || (self::$lastCleared + self::$interval < $now)
        ) {
            self::forceClear($filename);
            self::$lastCleared = $now;
        }
    }

    public static function forceClear(?string $filename = null): void
    {
        if ($filename !== null) {
            clearstatcache(true, $filename);
        } else {
            clearstatcache();
        }
    }

    public static function getInterval(): int
    {
        return self::$interval;
    }

    public static function setInterval(int $interval): self
    {
        self::$interval = $interval;
    }
}