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
        Schema::create('set_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('set_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('variant_id');
            $table->string('sku');
            $table->integer('set_quantity');
            $table->timestamps();

            $table->index('set_id', 'set_products_set_id_index');
            $table->foreign('set_id', 'set_products_set_id_fk')
                ->on('set_lists')
                ->references('id');

            $table->index('product_id', 'set_products_product_id_index');
            $table->foreign('product_id', 'set_products_product_id_fk')
                ->on('products')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_products');
    }
};
