<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $barangCount = Barang::count();
        $kategoriCount = Kategori::count();
        $barangMasukCount = BarangMasuk::count();
        $barangKeluarCount = BarangKeluar::count();

        return Inertia::render('Dashboard', [
            'barangCount' => $barangCount,
            'kategoriCount' => $kategoriCount,
            'barangMasukCount' => $barangMasukCount,
            'barangKeluarCount' => $barangKeluarCount,
        ]);
    }
}