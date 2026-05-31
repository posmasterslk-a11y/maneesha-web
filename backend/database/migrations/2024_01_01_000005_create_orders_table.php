<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // e.g. MF-20240101-0001
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Customer info (stored at time of order for guests too)
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('customer_address');
            $table->string('customer_city');
            $table->string('customer_postal_code')->nullable();

            // Order totals
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Payment
            $table->enum('payment_method', ['payhere', 'cod', 'bank_deposit'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payhere_order_id')->nullable();
            $table->string('bank_slip_path')->nullable(); // uploaded bank slip

            // Order lifecycle
            $table->enum('status', [
                'pending',      // Just placed
                'confirmed',    // Admin confirmed
                'processing',   // Being prepared
                'dispatched',   // Shipped out
                'delivered',    // Delivered to customer
                'cancelled',    // Cancelled
            ])->default('pending');

            $table->string('tracking_number')->nullable();
            $table->text('notes')->nullable();           // customer notes
            $table->text('admin_notes')->nullable();     // internal admin notes
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
