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
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('booking_number', 30)->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('rental_type_id')->constrained('rental_types')->cascadeOnDelete();
            $table->foreignId('vehicle_category_id')->constrained('vehicle_categories')->cascadeOnDelete();
            $table->foreignId('assigned_vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('assigned_driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('pickup_location_id')->constrained('pickup_locations')->cascadeOnDelete();
            $table->foreignId('return_location_id')->constrained('pickup_locations')->cascadeOnDelete();
            $table->dateTime('pickup_datetime')->index();
            $table->dateTime('return_datetime')->index();
            $table->dateTime('actual_pickup_datetime')->nullable();
            $table->dateTime('actual_return_datetime')->nullable();
            $table->string('booking_status', 30)->default('confirmed')->index(); // draft, confirmed, allocated, active, returned, settled, cancelled, completed
            $table->string('booking_source', 30)->default('direct_walkin'); // direct_walkin, phone, website, corporate, travel_agent
            $table->foreignId('package_id')->nullable()->constrained('rental_packages')->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('promotional_coupons')->nullOnDelete();
            $table->string('flight_number', 30)->nullable();
            $table->text('destination_notes')->nullable();
            
            // Financial Snapshots
            $table->decimal('base_rental_amount', 12, 2)->default(0.00);
            $table->decimal('driver_allowance_amount', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00); // GST
            $table->decimal('total_estimated_amount', 12, 2)->default(0.00);
            $table->decimal('security_deposit_amount', 10, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('service_name', 100); // child_seat, gps_navigator, additional_driver, interstate_permit_assistance
            $table->decimal('rate', 8, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });

        Schema::create('booking_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('old_status', 30);
            $table->string('new_status', 30);
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason');
            $table->decimal('cancellation_fee', 10, 2)->default(0.00);
            $table->decimal('refund_amount', 10, 2)->default(0.00);
            $table->string('refund_status', 30)->default('pending'); // pending, processed, waived
            $table->timestamps();
        });

        Schema::create('booking_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->dateTime('old_return_datetime');
            $table->dateTime('new_return_datetime');
            $table->integer('additional_hours')->default(0);
            $table->decimal('additional_amount', 10, 2)->default(0.00);
            $table->string('status', 30)->default('approved'); // pending, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_extensions');
        Schema::dropIfExists('booking_cancellations');
        Schema::dropIfExists('booking_status_history');
        Schema::dropIfExists('booking_services');
        Schema::dropIfExists('bookings');
    }
};

