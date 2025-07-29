<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Update invalid categories to 'shroom'
        DB::table('products')
            ->whereNotIn('category', ['shroom', 'cap', 'sheet'])
            ->update(['category' => 'shroom']);

        Schema::table('products', function (Blueprint $table) {
            $table->enum('category', ['shroom', 'cap', 'sheet'])->default('shroom')->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->default('shroom')->change();
        });
    }
};