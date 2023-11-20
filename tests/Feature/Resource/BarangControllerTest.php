<?php

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;

test('can return index page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/barang');

    $response->assertOk();
});

test('can return create page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/barang/create');

    $response->assertOk();
});

test('can store barang', function () {
    $user = User::factory()->create();
    Kategori::factory(1)->create();

    $response = $this
        ->actingAs($user)
        ->post('/barang', [
            'merk' => 'Test Merk',
            'seri' => 'Test Seri',
            'stok' => 10,
            'spesifikasi' => 'Test Spesifikasi',
            'kategori_id' => 1
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/barang');

    $this->assertDatabaseHas('barang', [
        'merk' => 'Test Merk',
        'seri' => 'Test Seri',
        'stok' => 10,
        'spesifikasi' => 'Test Spesifikasi',
        'kategori_id' => 1
    ]);
});

test('can return edit page', function () {
    $user = User::factory()->create();
    $kategori = Kategori::factory()->create();
    $barang = Barang::factory()->create(['kategori_id' => $kategori->id]);

    $response = $this
        ->actingAs($user)
        ->get("/barang/{$barang->slug}/edit");

    $response->assertOk();
});


test('can update barang', function () {
    $user = User::factory()->create();
    $kategori = Kategori::factory()->create();
    $barang = Barang::factory()->create(['kategori_id' => $kategori->id]);

    $response = $this
        ->actingAs($user)
        ->patch("/barang/{$barang->slug}", [
            'merk' => 'Test Merk',
            'seri' => 'Test Seri',
            'spesifikasi' => 'Test Spesifikasi',
            'kategori_id' => $kategori->id
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/barang');

    $barang->refresh();

    $this->assertSame('Test Merk', $barang->fresh()->merk);
    $this->assertSame('Test Seri', $barang->fresh()->seri);
    $this->assertSame('Test Spesifikasi', $barang->fresh()->spesifikasi);
    $this->assertSame($kategori->id, $barang->fresh()->kategori_id);
});

// test('can delete barang', function () {
//     $user = User::factory()->create();
//     $kategori = Kategori::factory()->create();
//     $barang = Barang::factory()->create(['kategori_id' => $kategori->id]);

//     $response = $this
//         ->actingAs($user)
//         ->delete("/barang/{$barang->slug}");

//     $response
//         ->assertSessionHasNoErrors()
//         ->assertRedirect('/barang');

//     $this->assertDatabaseMissing('barang', [
//         'slug' => $barang->slug
//     ]);
// });
