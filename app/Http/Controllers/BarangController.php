<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $barangs = Barang::with('kategori')->get();

        return Inertia::render('Barang/Index', [
            'barangs' => $barangs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $kategoris = Kategori::all();

        return Inertia::render('Barang/Create', [
            'kategoris' => $kategoris
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
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
    
            return Redirect::route('barang.index')->with('success', 'Barang berhasil disimpan');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
    
            return Redirect::back()->with('error', 'Barang gagal disimpan!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang): Response
    {
        $barang->load('kategori');
        return Inertia::render('Barang/Show', [
            'barang' => $barang
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang): Response
    {
        $kategoris = Kategori::all();

        return Inertia::render('Barang/Edit', [
            'barang' => $barang,
            'kategoris' => $kategoris
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang): RedirectResponse
    {
        
        DB::beginTransaction();
        try {
            $request->validate([
                'merk' => ['required', 'string', 'max:255'],
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
    
            $slug = Str::slug($request->merk . ' ' . $request->seri);
    
            $barang->update([
                'merk' => $request->merk,
                'slug' => $slug,
                'seri' => $request->seri,
                'spesifikasi' => $request->spesifikasi,
                'kategori_id' => $request->kategori_id
            ]);
            DB::commit();
    
            return Redirect::route('barang.index')->with('success', 'Barang berhasil diperbarui');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
    
            return Redirect::route('barang.index')->with('error', 'Barang gagal diperbarui!' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang): RedirectResponse
    {
        DB::beginTransaction();
        try {
            if ($barang->gambar) {
                Storage::delete('public/barang/' . $barang->gambar);
                }
            $barang->delete();
            DB::commit();
            return Redirect::route('barang.index')->with('success', 'Barang berhasil dihapus');
            
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return Redirect::back()->with('error', 'Barang gagal dihapus!');
        }
    }
}
