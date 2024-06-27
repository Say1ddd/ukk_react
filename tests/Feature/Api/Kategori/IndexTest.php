<?php

use App\Models\Kategori;

test('should return all kategori', function () {
    Kategori::factory()->count(5)->create();

    $response = $this->getJson('/api/kategori');

    $response->assertStatus(200)
        ->assertJsonCount(5, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'kategori',
                    'deskripsi',
                    'slug',
                ]
            ]
        ]);
})->group('kategori');
