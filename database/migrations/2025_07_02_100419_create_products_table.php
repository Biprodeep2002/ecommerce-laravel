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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->enum('type', ['physical', 'digital']);
            $table->decimal('weight', 8, 2)->nullable();     // for physical
            $table->decimal('filesize', 8, 2)->nullable();   // for digital
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
