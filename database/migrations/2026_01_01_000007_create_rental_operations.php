<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('agreement_number', 40)->unique();
            $table->string('terms_version', 20)->default('1.0');
            $table->string('signed_by_customer_name');
            $table->longText('customer_signature_data')->nullable(); // Base64 digital signature
            $table->timestamp('signed_at');
            $table->string('status', 30)->default('active'); // active, closed, voided
            $table->timestamps();
        });

        Schema::create('vehicle_handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('officer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->dateTime('handover_datetime');
            $table->unsignedInteger('odometer_start');
            $table->decimal('fuel_level_start', 4, 2)->default(8.00); // 8/8 scale
            $table->boolean('cleanliness_interior')->default(true);
            $table->boolean('cleanliness_exterior')->default(true);
            $table->boolean('spare_tyre_present')->default(true);
            $table->boolean('jack_tools_present')->default(true);
            $table->boolean('documents_in_car')->default(true);
            $table->longText('customer_signature')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_handover_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('handover_id')->constrained('vehicle_handovers')->cascadeOnDelete();
            $table->string('item_name', 100);
            $table->string('condition', 30)->default('good'); // good, fair, scratch, dent, missing
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('receiving_officer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->dateTime('return_datetime');
            $table->unsignedInteger('odometer_end');
            $table->decimal('fuel_level_end', 4, 2)->default(8.00);
            $table->unsignedInteger('excess_km')->default(0);
            $table->unsignedInteger('late_hours')->default(0);
            $table->boolean('cleaning_required')->default(false);
            $table->decimal('cleaning_charge', 8, 2)->default(0.00);
            $table->decimal('fuel_shortfall_litres', 6, 2)->default(0.00);
            $table->decimal('fuel_shortfall_charge', 8, 2)->default(0.00);
            $table->longText('customer_signature')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_damage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->string('report_type', 40)->default('return_inspection'); // handover_baseline, return_inspection, on_trip_incident
            $table->string('severity', 30)->default('minor'); // minor, moderate, major
            $table->string('body_location', 50); // front_bumper, rear_bumper, left_door, right_door, windshield, roof, etc.
            $table->text('description');
            $table->decimal('estimated_repair_cost', 10, 2)->default(0.00);
            $table->boolean('is_customer_liable')->default(true);
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });

        Schema::create('damage_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('damage_report_id')->constrained('vehicle_damage_reports')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->decimal('amount_charged', 10, 2);
            $table->boolean('is_billed_to_deposit')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('trip_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();
            $table->dateTime('trip_start_datetime');
            $table->unsignedInteger('garage_start_km');
            $table->dateTime('trip_end_datetime')->nullable();
            $table->unsignedInteger('garage_end_km')->nullable();
            $table->unsignedInteger('total_trip_km')->default(0);
            $table->integer('night_halts')->default(0);
            $table->decimal('toll_parking_amount', 10, 2)->default(0.00);
            $table->decimal('driver_bata_amount', 10, 2)->default(0.00);
            $table->integer('customer_rating')->nullable();
            $table->text('customer_comments')->nullable();
            $table->timestamps();
        });

        Schema::create('trip_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_record_id')->constrained('trip_records')->cascadeOnDelete();
            $table->string('expense_type', 40); // toll, parking, border_tax, state_entry, fuel
            $table->decimal('amount', 8, 2);
            $table->string('receipt_number', 50)->nullable();
            $table->string('receipt_photo')->nullable();
            $table->timestamps();
        });

        Schema::create('rental_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->decimal('base_rental_charged', 10, 2)->default(0.00);
            $table->decimal('excess_km_charged', 10, 2)->default(0.00);
            $table->decimal('late_hours_charged', 10, 2)->default(0.00);
            $table->decimal('fuel_deficit_charged', 10, 2)->default(0.00);
            $table->decimal('damage_charges', 10, 2)->default(0.00);
            $table->decimal('toll_parking_charges', 10, 2)->default(0.00);
            $table->decimal('discount_applied', 10, 2)->default(0.00);
            $table->decimal('tax_charged', 10, 2)->default(0.00);
            $table->decimal('gross_final_amount', 12, 2)->default(0.00);
            $table->decimal('advance_paid', 10, 2)->default(0.00);
            $table->decimal('deposit_held', 10, 2)->default(0.00);
            $table->decimal('deposit_deductions', 10, 2)->default(0.00);
            $table->decimal('deposit_refund_amount', 10, 2)->default(0.00);
            $table->decimal('balance_payable_by_customer', 10, 2)->default(0.00);
            $table->foreignId('settled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_settlements');
        Schema::dropIfExists('trip_expenses');
        Schema::dropIfExists('trip_records');
        Schema::dropIfExists('damage_charges');
        Schema::dropIfExists('vehicle_damage_reports');
        Schema::dropIfExists('vehicle_returns');
        Schema::dropIfExists('vehicle_handover_checklists');
        Schema::dropIfExists('vehicle_handovers');
        Schema::dropIfExists('rental_agreements');
    }
};

