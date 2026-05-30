<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'activity', 'description', 'icon', 'color'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($activity, $description = null, $icon = 'fa-info-circle', $color = 'blue', $user_id = null)
    {
        return self::create([
            'user_id' => $user_id ?? auth()->id(),
            'activity' => $activity,
            'description' => $description,
            'icon' => $icon,
            'color' => $color
        ]);
    }
}
