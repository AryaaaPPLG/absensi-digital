<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

    /**
     * Get the user that owns the leave.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
