<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Keuangan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = 'id';
    protected $fillable = [
        'keterangan',
        'jumlah',
        'user_id',
        'saldo',
        'kategori'
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
