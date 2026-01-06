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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productid')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('adminid')->nullable()->constrained('admins')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('price')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
