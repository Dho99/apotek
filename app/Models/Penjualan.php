<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $fillable = [
        'kodePenjualan',
        'produk_id',
        'apoteker_id',
        'dokter_id',
        'pasien_id',
        'kategoriPenjualan',
        'harga',
        'dsc',
        'jumlah',
        'subtotal'
    ];




    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function obat()
    {
        return $this->belongsTo(\App\Models\Produk::class, 'produk_id');
    }

    public function apoteker()
    {
        return $this->belongsTo(\App\Models\User::class, 'apoteker_id');
    }

    public function dokter()
    {
        return $this->belongsTo(\App\Models\User::class, 'dokter_id');
    }

    public function pasien()
    {
        return $this->belongsTo(\App\Models\User::class, 'pasien_id');
    }

}

