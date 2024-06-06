<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';

    protected $fillable = [
        'tanggal_keluar',
        'jumlah',
        'barang_id',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    use HasFactory;
}
