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
        Schema::create('service_orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_number')->unique();       // no. struk: SRV-20240601-001
    $table->string('customer_name');
    $table->string('vehicle_type');                 // misal: Honda Beat, Toyota Avanza
    $table->string('plate_number');                 // nomor plat
    $table->text('complaint')->nullable();          // keluhan pelanggan
    $table->enum('status', ['open', 'in_progress', 'done', 'cancelled'])->default('open');
    $table->decimal('service_fee', 12, 2)->default(0);  // biaya jasa
    $table->decimal('total_items_cost', 12, 2)->default(0); // total biaya barang
    $table->decimal('grand_total', 12, 2)->default(0);
    $table->text('notes')->nullable();              // catatan kasir
    $table->foreignId('user_id')->constrained();    // kasir yang input
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
