<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriDeskripsi = [
            'M' => 'Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai',
        ];
    
        foreach ($kategoriDeskripsi as $kategori => $deskripsi) {
            Kategori::create([
                'kategori' => $kategori,
                'deskripsi' => $deskripsi,
                'slug' => Str::slug($deskripsi),
            ]);
        }
    }
}
