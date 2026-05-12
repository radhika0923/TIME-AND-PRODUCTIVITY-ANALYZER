<?php

namespace App\Support;

final class Duration
{
    /**
     * Human-readable focus duration (stored as seconds).
     */
    public static function format(int $seconds): string
    {
        $seconds = max(0, $seconds);
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        $s = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    /**
     * Hours with one decimal from seconds (for dashboard totals).
     */
    public static function toDecimalHours(int $seconds): float
    {
        return round($seconds / 3600, 1);
    }
}
