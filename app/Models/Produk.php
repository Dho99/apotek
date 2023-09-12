<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $fillable = [
        'kode',
        'namaProduk',
        'satuan',
        'stok',
        'harga',
        'supplier_id',
        'golongan_id',
        'expDate',
        'image',
    ];



    /**
     * Get the user that owns the Produk
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function golongan(){
        return $this->belongsTo(Kategori::class, 'golongan_id');
    }
}
