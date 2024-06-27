<?php

test('should store kategori', function () {
    $response = $this->postJson('/api/kategori', [
        'kategori' => 'Kategori Baru',
        'deskripsi' => 'Deskripsi Kategori Baru',
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'message' => 'Kategori berhasil dibuat',
            'data' => [
                'kategori' => 'Kategori Baru',
                'deskripsi' => 'Deskripsi Kategori Baru',
            ]
        ]);
})->group('kategori');
