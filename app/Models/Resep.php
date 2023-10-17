<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $fillable = [
        'kode',
        'obat_id',
        'pasien_id',
        'gejala',
        'jumlah',
        'dokter_id',
        'apoteker_id',
        'isSuccess',
        'alasanPenolakan',
        'catatan',
        'catatanDokter',
        'isProceed',
        'isProceedByApoteker',
        'keteranganResep'
    ];


    /**
     * Get the user that owns the Resep
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    public function obat()
    {
        return $this->belongsTo(Produk::class, 'obat_id');
    }
    public function apoteker()
    {
        return $this->belongsTo(User::class, 'apoteker_id');
    }
}
