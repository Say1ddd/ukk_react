<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // DB::statement('
        //     CREATE FUNCTION kategoriFunction(kategori varchar(255)) RETURNS VARCHAR(255)
        //     BEGIN
        //         DECLARE kategori VARCHAR(255);
        //         IF kategori = "M" THEN
        //             SET kategori = "Modal Barang";
        //         ELSEIF kategori = "A" THEN
        //             SET kategori = "Alat";
        //         ELSEIF kategori = "BHP" THEN
        //             SET kategori = "Bahan Habis Pakai";
        //         ELSEIF kategori = "BTHP" THEN
        //             SET kategori = "Bahan Tidak Habis Pakai";
        //         END IF;
        //         RETURN kategori;
        //     END
        // ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // DB::statement('DROP FUNCTION IF EXISTS kategoriFunction');
    }
};
