<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'status',
        'method',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
