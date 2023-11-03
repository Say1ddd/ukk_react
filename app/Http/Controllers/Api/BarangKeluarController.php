<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $barangKeluar = BarangKeluar::with('barang')->latest()->get();
        return response()->json(["Data" => $barangKeluar], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $tanggalMasuk = BarangMasuk::where('barang_id', $request->barang_id)->latest()->first();
            $remainingStok = Barang::findOrFail($request->barang_id)->stok;
    
            $request->validate([
                'tanggal_keluar' => ['required', 'date', $tanggalMasuk ? 'after:' . $tanggalMasuk->tanggal_masuk : ''],
                'jumlah' => ['required', 'numeric', 'min:1', 'max:' . $remainingStok],
                'barang_id' => ['required', 'exists:barang,id'],
            ], [
                'jumlah.max' => 'Jumlah barang keluar tidak boleh melebihi stok barang',
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
    
            return response()->json(['status' => 'Barang keluar berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Barang keluar gagal ditambahkan'], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $barangKeluar = BarangKeluar::with('barang')->findOrFail($id);

        if (!$barangKeluar) {
            return response()->json(['status' => 'Barang keluar tidak ditemukan'], 404);
        }

        return response()->json(['data' => $barangKeluar], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $barangKeluar = BarangKeluar::findOrFail($id);
            $tanggalMasuk = BarangMasuk::where('barang_id', $barangKeluar->barang_id)->latest()->first();
            $remainingStok = Barang::findOrFail($request->barang_id)->stok + $barangKeluar->jumlah;
    
            $request->validate([
                'tanggal_keluar' => ['required', 'date', 'after:' . $tanggalMasuk->tanggal_masuk],
                'jumlah' => ['required', 'numeric', 'min:1', 'max:' . $remainingStok],
                'barang_id' => ['required', 'exists:barang,id'],
            ], [
                'jumlah.max' => 'Jumlah barang keluar tidak boleh melebihi stok barang',
                'required' => ':attribute harus diisi',
                'min' => ':attribute minimal :min',
                'jumlah.max' => ':attribute tidak boleh melebihi stok barang. sisa stok:' . $remainingStok,
                'after' => ':attribute harus setelah' . $tanggalMasuk->tanggal_masuk
            ]);
    
            $barangKeluar->update([
                'tanggal_keluar' => $request->tanggal_keluar,
                'jumlah' => $request->jumlah,
                'barang_id' => $request->barang_id,
            ]);
    
            DB::commit();
    
            return response()->json(['status' => 'Barang keluar berhasil diupdate'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Barang keluar gagal diupdate'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $barangKeluar = BarangKeluar::findOrFail($id);
            $barangKeluar->delete();
            DB::commit();
            return response()->json(['status' => 'Barang keluar berhasil dihapus'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Barang keluar gagal dihapus'], 401);
        }
    }
}
