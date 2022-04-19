<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->string('nama_artikel',255);
            $table->text('deskripsi');
            $table->date('tanggal_terbit');
            $table->unsignedBigInteger('id_staff');
            $table->foreign('id_staff')->references('id')->on('kelola_staff')->onDelete('cascade');
            $table->string('kategori',50);
            $table->string('gambar',255)->default(null);
            $table->boolean('hapus')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artikels');
    }
};
