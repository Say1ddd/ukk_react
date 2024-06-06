<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::with('kategori')->latest()->get();
        return Inertia::render('Barang/Index', [
            'barangs' => $barangs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::get();

        return Inertia::render('Barang/Create', [
            'kategoris' => $kategoris
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'merk' => ['required', 'max:255', 'unique:barang,merk'],
            'gambar' => ['nullable', 'image', 'max:1024', 'mimes:jpg,jpeg,png'],
            'seri' => ['required', 'max:255', 'unique:barang,seri'],
            'spesifikasi' => ['nullable'],
            'stok' => ['required', 'numeric', 'min:0'],
            'kategori_id' => ['required', 'exists:kategori,id'],
        ]);

        $gambar = $request->file('gambar');
        $gambar->storeAs('public/barang', $gambar->hashName());

        $slug = Str::slug($request->seri);

        Barang::create([
            'merk' => $request->merk,
            'gambar' => $gambar->hashName(),
            'slug' => $slug,
            'seri' => $request->seri,
            'spesifikasi' => $request->spesifikasi,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('barang.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        $kategori = $barang->kategori;

        return Inertia::render('Barang/Show', [
            'barang' => $barang,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $kategoris = Kategori::get();

        return Inertia::render('Barang/Edit', [
            'barang' => $barang,
            'kategoris' => $kategoris,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'merk' => 'required',
            'slug' => 'required',
            'seri' => 'required',
            'spesifikasi' => 'required',
            'stok' => 'required',
            'kategori_id' => 'required',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index');
    }
}
