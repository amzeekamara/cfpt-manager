<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Notifications\StaffActionNotification;
use Illuminate\Support\Facades\Notification;

class ActivityLogger
{
    public static function log($action, $description, $model = null)
    {
        $user = auth()->user();
        if (!$user) return;

        // 1. Create Log
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'ip_address' => request()->ip(),
        ]);

        // 2. Notify Admin if the action was by a Coordinateur
        if ($user->role && $user->role->name === 'Coordinateur') {
            $admins = User::whereHas('role', function($q) {
                $q->where('name', 'Administrateur');
            })->get();

            Notification::send($admins, new StaffActionNotification($user, $action, $description));
        }
    }
}
