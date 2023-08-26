<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    protected $fillable = [
        'noFaktur',
        'user_id',
        'supplier_id',
        'harga',
        'jumlah',
        'subtotal',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(App\Models\User::class, 'user_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(App\Models\Supplier::class, 'supplier_id');
    }
}
