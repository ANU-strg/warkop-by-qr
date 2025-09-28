<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meja extends Model
{
    protected $table = 'meja';

    protected $fillable = [
        'nomor_meja',
        'qr_code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relasi ke Order
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
