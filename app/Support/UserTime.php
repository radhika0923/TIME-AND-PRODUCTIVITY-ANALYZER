<?php

namespace App\Support;

use App\Models\User;
use Carbon\Carbon;

final class UserTime
{
    public static function timezone(User $user): string
    {
        return $user->timezone ?: (string) config('app.timezone');
    }

    /**
     * @return array{0: Carbon, 1: Carbon} UTC instants for [start, end] of "today" in the user's timezone.
     */
    public static function todayUtcRange(User $user): array
    {
        $tz = self::timezone($user);
        $start = Carbon::now($tz)->startOfDay()->utc();
        $end = Carbon::now($tz)->endOfDay()->utc();

        return [$start, $end];
    }

    /**
     * @return array{0: Carbon, 1: Carbon} UTC range for a calendar day (Y-m-d) in the user's timezone.
     */
    public static function dayUtcRange(User $user, string $ymd): array
    {
        $tz = self::timezone($user);
        $start = Carbon::createFromFormat('Y-m-d', $ymd, $tz)->startOfDay()->utc();
        $end = Carbon::createFromFormat('Y-m-d', $ymd, $tz)->endOfDay()->utc();

        return [$start, $end];
    }
}
