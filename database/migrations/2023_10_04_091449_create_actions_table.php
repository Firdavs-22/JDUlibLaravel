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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('action_table', [
                'user', 'student', 'book', 'category', 'book_series', 'occupied_book'
            ])->nullable(false);
            $table->enum('action', [
                'create', 'update', 'delete', 'other'
            ])->nullable(false);
            $table->text('describe');
            $table->timestamp('created_date')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
