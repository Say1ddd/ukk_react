<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $kategoris = Kategori::latest()->get();

        return Inertia::render('Kategori/Index', [
            'kategoris' => $kategoris
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Kategori/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'deskripsi' => ['required', 'string', 'max:255'],
                'kategori' => ['required', 'string', 'max:30'],
            ], [
                'required' => ':attribute harus diisi',
                'max' => ':attribute maksimal :max karakter'
            ]);
    
            $slug = Str::slug($request->deskripsi);
    
            Kategori::create([
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
                'slug' => $slug
            ]);
    
            DB::commit();
            return Redirect::route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return Redirect::back()->with('error', 'Kategori gagal ditambahkan!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori): Response
    {
        return Inertia::render('Kategori/Show', [
            'kategori' => $kategori
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori): Response
    {
        return Inertia::render('Kategori/Edit', [
            'kategori' => $kategori
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'deskripsi' => ['required', 'string', 'max:255'],
                'kategori' => ['required', 'string', 'max:30'],
            ], [
                'required' => ':attribute harus diisi',
                'max' => ':attribute maksimal :max karakter'
            ]);
    
            $slug = Str::slug($request->deskripsi);
    
            $kategori->update([
                'deskripsi' => $request->deskripsi,
                'kategori' => $request->kategori,
                'slug' => $slug
            ]);
    
            DB::commit();
            return Redirect::route('kategori.index')->with('success', 'Kategori berhasil diupdate');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return Redirect::back()->with('error', 'Kategori gagal diupdate!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $kategori->delete();
            DB::commit();
            return Redirect::route('kategori.index')->with('success', 'Kategori berhasil dihapus');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return Redirect::back()->with('error', 'Kategori gagal dihapus!');
        }
    }
}
