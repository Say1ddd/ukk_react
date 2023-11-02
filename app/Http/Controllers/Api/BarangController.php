<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $barangs = Barang::with('kategori')->get();

        return response()->json(["data" => $barangs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'merk' => ['required', 'string', 'max:255'],
            'gambar' => ['nullable', 'image', 'max:1024', 'mimes:jpg,jpeg,png'],
            'seri' => ['required', 'string', 'max:255', 'unique:barang,seri'],
            'stok' => ['required', 'numeric', 'min:0'],
            'spesifikasi' => ['nullable', 'string'],
            'kategori_id' => ['nullable', 'exists:kategori,id', 'numeric']
        ], [
            'required' => ':attribute harus diisi',
            'image' => ':attribute harus berupa gambar',
            'max' => ':attribute maksimal :max KB',
            'mimes' => ':attribute harus menggunakan format jpg, jpeg, atau png',
            'unique' => ':attribute sudah terdaftar',
            'exists' => ':attribute tidak ditemukan'
        ]);
    
        DB::beginTransaction();
        try {
            if ($request->gambar) {
                $gambar = $request->file('gambar')->storeAs('public/barang', $request->file('gambar')->hashName());
            } else {
                $gambar = null;
            }
    
            $slug = Str::slug($request->merk . ' ' . $request->seri);
    
            $barangRecord = Barang::create([
                'merk' => $request->merk,
                'gambar' => $gambar,
                'slug' => $slug,
                'stok' => 0,
                'seri' => $request->seri,
                'spesifikasi' => $request->spesifikasi,
                'kategori_id' => $request->kategori_id
            ]);
    
            if ($request->stok > 0) {
                BarangMasuk::create([
                    'barang_id' => $barangRecord->id,
                    'jumlah' => $request->stok,
                    'tanggal_masuk' => now()
                ]);
            }
    
            DB::commit();
    
            return response()->json(['data' => $barangRecord], 201);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'status' => 'Barang gagal disimpan'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang): JsonResponse
    {
        $barang->load('kategori');
        return response()->json(["data" => $barang]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang): JsonResponse
    {
        
        DB::beginTransaction();
        try {
            $request->validate([
                'merk' => ['required', 'string', 'max:255'],
                'gambar' => ['nullable', 'image', 'max:1024', 'mimes:jpg,jpeg,png'],
                'seri' => ['required', 'string', 'max:255', 'unique:barang,seri,' . $barang->id],
                'spesifikasi' => ['nullable', 'string'],
                'kategori_id' => ['nullable', 'exists:kategori,id', 'numeric']
            ], [
                'required' => ':attribute harus diisi',
                'image' => ':attribute harus berupa gambar',
                'max' => ':attribute maksimal :max KB',
                'mimes' => ':attribute harus menggunakan format jpg, jpeg, atau png',
                'unique' => ':attribute sudah terdaftar',
                'exists' => ':attribute tidak ditemukan'
            ]);

            if ($request->gambar) {
                if ($barang->gambar) {
                    Storage::delete('public/barang/' . $barang->gambar);
                }
                $gambar = $request->file('gambar')->storeAs('public/barang', $request->file('gambar')->hashName());
            } else {
                $gambar = $barang->gambar;
            }
    
            $slug = Str::slug($request->merk . ' ' . $request->seri);
    
            $barang->update([
                'merk' => $request->merk,
                'gambar' => $gambar,
                'slug' => $slug,
                'seri' => $request->seri,
                'spesifikasi' => $request->spesifikasi,
                'kategori_id' => $request->kategori_id
            ]);
            DB::commit();
    
            return response()->json(['data' => $barang], 201);
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'status' => 'Barang gagal diupdate'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang): JsonResponse
    {
        DB::beginTransaction();
        try {
            if ($barang->gambar) {
                Storage::delete('public/barang/' . $barang->gambar);
                $barang->delete();
            }
            DB::commit();
            return response()->json(['status' => 'Barang berhasil dihapus'], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'Barang gagal dihapus'], 400);
        }
    }
}
