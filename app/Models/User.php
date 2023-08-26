<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    // protected $table = 'users';

    protected $fillable = [
        'username',
        'kode',
        'nama',
        'email',
        'password',
        'telp',
        'alamat',
        'status',
        'level',
        'profile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function level(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  ["Dokter", "Apoteker", "Kasir", "Pasien"][$value],
        );
    }
}
