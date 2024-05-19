<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $guarded = ['id', 'no_rekam_medis'];

    protected function getAll()
    {
        return $this->where('roleId', 2)->get();
    }

    protected function isPresent()
    {
        return $this->where('isPresent', 1)->get();
    }

}
