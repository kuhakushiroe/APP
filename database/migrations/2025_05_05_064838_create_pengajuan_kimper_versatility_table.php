<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_kimper_versatility', function (Blueprint $table) {
            $table->id();
            $table->string('id_pengajuan_kimper');
            $table->string('id_versatility');
            $table->enum('klasifikasi', ['F', 'R', 'T', 'I']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kimper_versatility');
    }
};
