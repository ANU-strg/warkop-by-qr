<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'meja_id',
        'order_number',
        'total_harga',
        'status',
        'catatan',
        'waktu_pesan'
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'waktu_pesan' => 'datetime'
    ];

    /**
     * Relasi ke Meja
     */
    public function meja(): BelongsTo
    {
        return $this->belongsTo(Meja::class);
    }

    /**
     * Relasi ke OrderDetail
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
