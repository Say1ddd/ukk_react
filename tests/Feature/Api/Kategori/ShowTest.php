<?php

use App\Models\Kategori;

test('should show kategori', function () {
    $kategori = Kategori::factory()->create();

    $response = $this->getJson("/api/kategori/{$kategori->slug}");

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $kategori->id,
                'kategori' => $kategori->kategori,
                'deskripsi' => $kategori->deskripsi,
                'slug' => $kategori->slug,
            ]
        ]);
})->group('kategori');