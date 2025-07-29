<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->decimal('price', 8, 2);
            $table->string('image');
            $table->integer('stock');
            $table->enum('category', ['shroom', 'cap', 'sheet', 'pet_food', 'flowers']); // Use ENUM for strict categories
            $table->boolean('is_new')->default(false);
            $table->boolean('is_on_sale')->default(false);
            $table->text('description')->nullable();
            $table->float('average_rating')->default(0);
            $table->integer('review_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};