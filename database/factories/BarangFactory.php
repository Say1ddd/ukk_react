<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $merk = fake()->word;

        return [
            'nama' => $merk,
            'slug' => Str::slug($merk),
            'seri' => fake()->word,
            'spesifikasi' => fake()->sentence,
            'kategori_id' => rand(1, 4),
            'stok' => fake()->randomNumber(),
        ];
    }
}
