<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

    protected $fillable = [
        'tanggal_masuk',
        'jumlah',
        'barang_id',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    use HasFactory;
}
