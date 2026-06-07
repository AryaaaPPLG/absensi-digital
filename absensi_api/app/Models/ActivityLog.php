<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // Tentukan kolom mana saja yang boleh diisi secara massal
    protected $fillable = ['user_id', 'activity', 'description', 'icon', 'color'];

    /**
     * Relasi: Log aktivitas ini dimiliki oleh seorang User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Fungsi Static untuk mencatat log aktivitas dengan mudah.
     * Cara penggunaan: ActivityLog::log('Judul', 'Deskripsi', 'icon', 'warna');
     */
    public static function log($activity, $description = null, $icon = 'fa-info-circle', $color = 'blue', $user_id = null)
    {
        // Simpan data log baru ke tabel activity_logs
        return self::create([
            // Jika user_id tidak diisi, otomatis ambil ID user yang sedang login
            'user_id' => $user_id ?? auth()->id(),
            'activity' => $activity,
            'description' => $description,
            'icon' => $icon,
            'color' => $color
        ]);
    }
}
