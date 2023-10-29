<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $fillable = [
        'golongan',
        'keterangan'
    ];
    use HasFactory;


    public function golonganObat(){
        return $this->hasMany(Produk::class, 'golongan_id');
    }
}
