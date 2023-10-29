<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'slug',
        'deskripsi',
        'kategori',
    ];

    use HasFactory;

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
