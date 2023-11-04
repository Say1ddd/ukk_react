<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $barangmasuks = BarangMasuk::with('barang')->latest()->get();
        return response()->json(["Data" => $barangmasuks], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'tanggal_masuk' => ['required', 'date'],
                'jumlah' => ['required', 'numeric', 'min:1'],
                'barang_id' => ['required', 'exists:barang,id'],
            ], [
                'required' => ':attribute harus diisi',
                'min' => ':attribute minimal :min',
            ]);
    
            BarangMasuk::create([
                'tanggal_masuk' => $request->tanggal_masuk,
                'jumlah' => $request->jumlah,
                'barang_id' => $request->barang_id,
            ]);
    
            DB::commit();
            return response()->json(['status' => 'Barang masuk berhasil ditambahkan'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Barang masuk gagal ditambahkan'], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $barangMasuk = BarangMasuk::with('barang')->find($id);

        if (!$barangMasuk) {
            return response()->json(['status' => 'Barang masuk tidak ditemukan'], 404);
        }

        return response()->json(['data' => $barangMasuk], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $currentStok = Barang::findOrFail($request->barang_id)->stok;
    
            $request->validate([
                'tanggal_masuk' => ['required', 'date'],
                'jumlah' => ['required', 'numeric', 'min:' . $currentStok],
                'barang_id' => ['required', 'exists:barang,id'],
            ], [
                'required' => ':attribute harus diisi',
                'min' => ':attribute minimal :min',
                'jumlah.max' => ':attribute tidak boleh kurang dari stok barang. sisa stok:' . $currentStok
            ]);
    
            $barangMasuk = BarangMasuk::findOrFail($id);
    
            $barangMasuk->update([
                'tanggal_masuk' => $request->tanggal_masuk,
                'jumlah' => $request->jumlah,
                'barang_id' => $request->barang_id,
            ]);
    
            DB::commit();
            return response()->json(['status' => 'Barang masuk berhasil diubah'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Barang masuk gagal diubah'], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $barangMasuk = BarangMasuk::findOrFail($id);
            $barangMasuk->delete();
    
            DB::commit();
            return response()->json(['status' => 'Barang masuk berhasil dihapus'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Barang masuk gagal dihapus'], 401);
        }
    }
}
