<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    protected function getAll()
    {
        return $this->all();
    }

    public function patient()
    {
        return $this->belongsTo(\App\Models\Pasien::class, 'pasienId');
    }
}
