<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateProductionBatchesTable extends Migration
{
    public function up()
    {
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('production_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->date('production_date');
            $table->integer('planned_quantity');
            $table->integer('actual_quantity')->nullable();
            $table->enum('quality_status', ['excellent', 'good', 'fair', 'poor'])->nullable();
            $table->date('expiry_date');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_batches');
    }
};
