<?php

namespace App\Http\Controllers\Api;

use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $kategoris = Kategori::get();
        return response()->json(["data" => $kategoris]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'kategori' => ['required', 'max:255', 'unique:kategori,kategori'],
            'deskripsi' => ['required', 'max:255']
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada'
        ]);

        $kategoriMapping = [
            'M' => 'Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai'
        ];

        $kategori = $request->kategori;
        $slug = $kategoriMapping[$kategori] ?? Str::slug($kategori);

        $create = Kategori::create([
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'kategori' => $kategori
        ]);

        return response()->json([
            'message' => 'Kategori berhasil dibuat',
            'data' => $create
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $kategori = Kategori::where('slug', $id)->first();
        if (!$kategori) {
            return response()->json(['status' => 'Kategori tidak ditemukan'], 404);
        }
        return response()->json(["data" => $kategori]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $kategoriRecord = Kategori::where('slug', $id)->first();

        $validator = Validator::make($request->all(), [
            'kategori' => ['required', 'max:255', 'unique:kategori,kategori'],
            'deskripsi' => ['required', 'max:255']
        ], [
            'required' => ':attribute harus diisi',
            'unique' => ':attribute sudah ada'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $kategoriMapping = [
            'M' => 'Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai'
        ];

        $kategori = $request->kategori;
        $slug = $kategoriMapping[$kategori] ?? Str::slug($kategori);

        if (!$kategoriRecord) {
            return response()->json(['status' => 'Kategori tidak ditemukan'], 404);
        }

        $kategoriRecord->update([
            'slug' => $slug,
            'deskripsi' => $request->deskripsi,
            'kategori' => $kategori
        ]);

        return response()->json(['status' => 'Kategori berhasil diubah']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $kategori = Kategori::where('slug', $id)->first();
        if (!$kategori) {
            return response()->json(['status' => 'Kategori tidak ditemukan'], 404);
        }
        if ($kategori->barangs()->count() > 0) {
            return response()->json(['status' => 'Kategori tidak dapat dihapus'], 403);
        }
        $kategori->delete();
        return response()->json(['status' => 'Kategori berhasil dihapus']);
    }
}
