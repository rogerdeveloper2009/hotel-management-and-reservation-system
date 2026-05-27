<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('room_type_id')->constrained('room_types');
            $table->foreignId('room_id')->nullable()->constrained('rooms');

            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedSmallInteger('nights')->default(1);
            $table->unsignedSmallInteger('adults')->default(1);
            $table->unsignedSmallInteger('children')->default(0);

            $table->string('status')->default('pending'); // pending, confirmed, checked_in, checked_out, cancelled

            // Pricing snapshot (RWF)
            $table->decimal('rate_per_night', 12, 2);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('extra_services_amount', 12, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0); // percent
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_amount', 12, 2)->default(0);

            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'check_in_date', 'check_out_date']);
            $table->index(['room_id', 'check_in_date', 'check_out_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

