<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_code')->unique();
            $table->foreignId('distributor_id')->constrained()->onDelete('cascade');
            $table->foreignId('delivery_schedule_id')->constrained()->onDelete('cascade');
            $table->date('order_date');
            $table->decimal('total_weight', 8, 2);
            $table->decimal('total_value', 12, 2);
            $table->integer('total_items');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->text('special_instructions')->nullable();
            $table->enum('status', [
                'pending', 'confirmed', 'packed', 'shipped',
                'in_transit', 'delivered', 'cancelled'
            ])->default('pending');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipments');
    }
};
