<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use function Pest\Laravel\from;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BarangMasuk>
 */
class BarangMasukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tanggal_masuk' => now(),
            'jumlah' => 100,
            'barang_id' => 1
        ];
    }
}
