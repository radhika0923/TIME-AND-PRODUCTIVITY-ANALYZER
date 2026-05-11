<?php

namespace App\Policies;

use App\Models\TimeLog;
use App\Models\User;

class TimeLogPolicy
{
    public function update(User $user, TimeLog $timeLog): bool
    {
        return $user->id === $timeLog->user_id;
    }

    public function delete(User $user, TimeLog $timeLog): bool
    {
        return $user->id === $timeLog->user_id;
    }

    public function export(User $user): bool
    {
        return true;
    }
}
