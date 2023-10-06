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
        Schema::create('occupied_books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_series_id');
            $table->unsignedBigInteger('student_id');
            $table->timestamp('occupied_date')->useCurrent();
            $table->timestamp('returned_date')->nullable();
            $table->enum('status', [
                'on', 'off'
            ])->default('on');

            $table->foreign('book_series_id')->references('id')->on('book_series');
            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occupied_books');
    }
};
