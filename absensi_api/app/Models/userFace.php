<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserFace extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'encoding'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
