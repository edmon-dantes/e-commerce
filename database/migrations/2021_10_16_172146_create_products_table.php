<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('sku')->unique();
            $table->float('price')->default(0);
            $table->bigInteger('stock')->default(0);
            $table->float('discount')->default(0);
            $table->text('description')->nullable();
            $table->text('details')->nullable();
            $table->string('fabric');
            $table->string('pattern');
            $table->string('sleeve');
            $table->string('fit');
            $table->string('occassion');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords');
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('status')->default(0);

            // $table->json('properties')->nullable();
            // $table->bigInteger('quantity')->default(0);
            // $table->boolean('active')->default(false);
            // $table->unsignedBigInteger('shop_id');
            // $table->decimal('purchase_price', 12, 2)->default(0);
            // $table->decimal('sale_price', 12, 2)->default(0);
            // $table->string('sku', 12);
            // $table->string('size', 12);

            // $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id');

            $table->string('slug')->unique();
            $table->string('fullname');

            $table->timestamps();

            // $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
