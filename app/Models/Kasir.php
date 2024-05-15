<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasir extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $guarded = ['id','no_rekam_medis','kategoriDokter', 'jamPraktek'];
}
