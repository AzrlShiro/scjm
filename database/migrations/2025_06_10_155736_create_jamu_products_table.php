<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJamuProductsTable extends Migration
{
    public function up()
    {
        Schema::create('jamu_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('type'); // jamu tradisional, kapsul, serbuk, dll
            $table->decimal('price', 10, 2);
            $table->integer('min_stock')->default(0);
            $table->integer('current_stock')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jamu_products');
    }
};
