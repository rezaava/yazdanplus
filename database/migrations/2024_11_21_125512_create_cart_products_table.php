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
        Schema::create('cart_products', function (Blueprint $table) {
            $table->id();
            $table->string('cart_id');
            $table->string('product_id');
        
            $table->string('price')->nullable();
            $table->tinyInteger('off')->default(0);
            $table->string('number');
            $table->string('total')->nullable();
            $table->timestamps();
            // $table->foreign('cart_id')->references('id')->on('cart')->onDelete('CASCADE')->onUpdate('CASCADE');
            // $table->foreign('product_id')->references('id')->on('product')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_products');
    }
};
