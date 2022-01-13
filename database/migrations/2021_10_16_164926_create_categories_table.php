<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->float('discount', 12, 2)->default(0);

            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords');

            $table->unsignedBigInteger('parent_id')->nullable()->default(null);
            $table->unsignedBigInteger('section_id');

            $table->tinyInteger('status')->default(0);
            $table->string('slug')->unique();

            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
