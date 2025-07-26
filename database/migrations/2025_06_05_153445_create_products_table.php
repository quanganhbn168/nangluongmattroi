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
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('code');
            $table->string('slug')->unique();
            $table->string('image');
            $table->string('banner')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('price_discount', 12, 2)->nullable();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->longText('specifications')->nullable();
            $table->boolean('status')->default(1);
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();   
            $table->string('meta_image')->nullable();   
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('categories')->onDelete('cascade');
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
