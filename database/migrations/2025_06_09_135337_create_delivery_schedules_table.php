<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('delivery_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_code')->unique();
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->date('delivery_date');
            $table->time('departure_time');
            $table->time('estimated_arrival_time');
            $table->string('vehicle_type');
            $table->string('vehicle_number');
            $table->string('driver_name');
            $table->string('driver_phone');
            $table->decimal('capacity_weight', 8, 2); // dalam kg
            $table->text('notes')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_schedules');
    }
};
