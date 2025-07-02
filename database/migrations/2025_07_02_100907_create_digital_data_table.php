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
        Schema::create('digital_data', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // same as product id
            $table->decimal('filesize', 8, 2)->nullable();
            $table->foreign('id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_data');
    }
};
