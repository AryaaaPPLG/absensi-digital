<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SchoolClass;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'rfid_uid',
        'class_id',
        'shift_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @KISI-KISI: DATABASE & ELOQUENT ORM (Relasi Tabel)
     * Relasi 'belongsTo' (One to Many - Inverse)
     * Menunjukkan bahwa User (Siswa) ini dimiliki oleh satu Kelas.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * @KISI-KISI: DATABASE & ELOQUENT ORM (Relasi Tabel)
     * Relasi 'belongsTo'
     * User (Siswa/Guru) memiliki satu Shift kerja/sekolah.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * @KISI-KISI: DATABASE & ELOQUENT ORM (Relasi Tabel)
     * Relasi 'hasMany' (One to Many)
     * Satu User bisa memiliki banyak catatan kehadiran (Attendance).
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * @KISI-KISI: DATABASE & ELOQUENT ORM (Relasi Tabel)
     * Relasi 'hasMany'
     * Satu User bisa mengajukan banyak Izin (Leave).
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }
}
