<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_series', function (Blueprint $table) {
            $table->id();
            $table->string('serial_id')->nullable(false);
            $table->unsignedBigInteger('book_id');
            $table->enum('status', ['on', 'off'])->default('on');

            $table->foreign('book_id')->references('id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_series');
    }
};
