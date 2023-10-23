<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $barangkeluars = BarangKeluar::with('barang')->latest()->get();
        return Inertia::render('BarangKeluar/Index', [
            'barangkeluars' => $barangkeluars,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Inertia\Response
    {
        $barangs = Barang::all();

        return Inertia::render('BarangKeluar/Create', [
            'barangs' => $barangs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $tanggalMasuk = BarangMasuk::where('barang_id', $request->barang_id)->latest()->first();
            $remainingStok = Barang::find($request->barang_id)->stok;
    
            $request->validate([
                'tanggal_keluar' => ['required', 'date', $tanggalMasuk ? 'after:' . $tanggalMasuk->tanggal_masuk : ''],
                'jumlah' => ['required', 'numeric', 'min:1', 'max:' . $remainingStok],
                'barang_id' => ['required', 'exists:barang,id'],
            ], [
                'required' => ':attribute harus diisi',
                'min' => ':attribute minimal :min',
                'jumlah.max' => ':attribute tidak boleh melebihi stok barang. sisa stok:' . $remainingStok,
                'after' => ':attribute harus setelah' . ($tanggalMasuk ? $tanggalMasuk->tanggal_masuk : 'tanggal masuk terbaru')
            ]);

            BarangKeluar::create([
                'tanggal_keluar' => $request->tanggal_keluar,
                'jumlah' => $request->jumlah,
                'barang_id' => $request->barang_id,
            ]);

            DB::commit();

            return Redirect::route('barangkeluar.index')->with('success', 'Barang keluar berhasil ditambahkan');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return Redirect::back()->with('error', 'Barang keluar gagal ditambahkan!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): \Inertia\Response
    {
        $barangKeluar = BarangKeluar::with('barang')->findOrFail($id);
    
        return Inertia::render('BarangKeluar/Show', [
            'barangkeluar' => $barangKeluar,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): \Inertia\Response
    {
        $barangs = Barang::all();

        $barangKeluar = BarangKeluar::findOrFail($id);

        $barangMasuk = BarangMasuk::where('barang_id', $barangKeluar->barang_id)->latest()->first();

        return Inertia::render('BarangKeluar/Edit', [
            'barangkeluar' => $barangKeluar,
            'barangs' => $barangs,
            'barangMasuk' => $barangMasuk
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $currentStok = Barang::findOrFail($request->barang_id)->stok + BarangKeluar::findOrFail($id)->jumlah;
            $tanggalMasuk = BarangMasuk::where('barang_id', $request->barang_id)->latest()->first();
    
            $request->validate([
                'tanggal_keluar' => ['required', 'date', $tanggalMasuk ? 'after_or_equal:' . $tanggalMasuk->tanggal_masuk : ''],
                'jumlah' => ['required', 'numeric', 'min:1', 'max:' . $currentStok],
                'barang_id' => ['required', 'exists:barang,id'],
            ], [
                'required' => ':attribute harus diisi',
                'min' => ':attribute minimal :min',
                'jumlah.max' => ':attribute tidak boleh melebihi stok barang. sisa stok:' . $currentStok
            ]);
    
            $barangKeluar = BarangKeluar::findOrFail($id);
    
            $barangKeluar->update([
                'tanggal_keluar' => $request->tanggal_keluar,
                'jumlah' => $request->jumlah,
                'barang_id' => $request->barang_id,
            ]);
    
            DB::commit();
    
            return Redirect::route('barangkeluar.index')->with('success', 'Barang keluar berhasil diubah');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return Redirect::back()->with('error', 'Barang keluar gagal diubah!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $barangKeluar = BarangKeluar::findOrFail($id);
            $barangKeluar->delete();
    
            DB::commit();
    
            return Redirect::route('barangkeluar.index')->with('success', 'Barang keluar berhasil dihapus');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return Redirect::back()->with('error', 'Barang keluar gagal dihapus!');
        }
    }
}
