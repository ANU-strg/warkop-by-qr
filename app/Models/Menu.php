<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'kategori',
        'gambar',
        'is_available'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    /**
     * Relasi ke OrderDetail
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
