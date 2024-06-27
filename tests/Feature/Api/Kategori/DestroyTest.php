<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('should return 404 if kategori not found', function () {
    $response = $this->deleteJson('/api/kategori/non-existent-slug');

    $response->assertNotFound()
        ->assertJson([
            "status" => "Kategori tidak ditemukan"
        ]);

})->group('kategori');
