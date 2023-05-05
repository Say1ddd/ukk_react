<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Barang;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $barangs = Barang::latest()->get();

        return Inertia::render('Dashboard', [
            'barangs' => $barangs,
            'kategoris' => $barangs->kategori
        ]) ;
    }
}
