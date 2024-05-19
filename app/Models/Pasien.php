<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $guarded = ['id', 'kategoriDokter','jamPraktek'];

    protected function getAll()
    {
        return $this->where('roleId', 3)->get();
    }

}
