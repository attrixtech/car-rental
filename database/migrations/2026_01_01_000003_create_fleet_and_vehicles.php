<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name'); // Hatchback, Sedan, SUV, Luxury, Van
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('seating_capacity')->default(5);
            $table->integer('baggage_capacity')->default(2);
            $table->decimal('base_hourly_rate', 10, 2)->default(100);
            $table->decimal('base_daily_rate', 10, 2)->default(1500);
            $table->decimal('security_deposit', 10, 2)->default(5000);
            $table->decimal('excess_km_rate', 8, 2)->default(12);
            $table->decimal('excess_hour_rate', 8, 2)->default(150);
            $table->integer('free_km_per_day')->default(250);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('logo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('vehicle_brands')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('vehicle_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('body_type', 50)->nullable(); // Hatchback, Sedan, SUV, MUV
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pickup_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('name');
            $table->string('location_type', 50)->default('branch'); // branch, airport, railway_station, hotel, city_hub
            $table->text('address')->nullable();
            $table->string('city', 100);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('convenience_fee', 8, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('vehicle_categories')->cascadeOnDelete();
            $table->foreignId('model_id')->constrained('vehicle_models')->cascadeOnDelete();
            $table->string('registration_number', 30)->unique();
            $table->string('vin_number', 50)->nullable()->unique();
            $table->string('engine_number', 50)->nullable();
            $table->string('color', 40)->default('White');
            $table->integer('manufacture_year')->default(2023);
            $table->string('fuel_type', 20)->default('diesel'); // petrol, diesel, electric, cng, hybrid
            $table->string('transmission', 20)->default('manual'); // manual, automatic
            $table->integer('seating_capacity')->default(5);
            $table->unsignedInteger('current_odometer')->default(0);
            $table->decimal('fuel_tank_capacity', 6, 2)->default(45); // in Litres
            $table->decimal('current_fuel_level', 5, 2)->default(8.00); // 8/8 full scale
            $table->string('status', 30)->default('available')->index(); // available, reserved, rented, maintenance, in_repair, decommissioned
            $table->string('ownership_type', 30)->default('owned'); // owned, leased, attached
            $table->foreignId('current_location_id')->nullable()->constrained('pickup_locations')->nullOnDelete();
            $table->decimal('daily_rental_price', 10, 2)->nullable();
            $table->decimal('hourly_rental_price', 10, 2)->nullable();
            $table->date('insurance_expiry_date')->nullable()->index();
            $table->date('fitness_expiry_date')->nullable();
            $table->date('permit_expiry_date')->nullable();
            $table->date('puc_expiry_date')->nullable();
            $table->boolean('is_gps_enabled')->default(false);
            $table->string('gps_device_imei', 50)->nullable();
            $table->json('features')->nullable(); // AC, Airbags, Bluetooth, Sunroof, etc.
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicle_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->string('document_type', 50); // rc, insurance, fitness, permit, puc
            $table->string('document_number', 50)->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable()->index();
            $table->string('file_path');
            $table->integer('alert_days_before')->default(30);
            $table->timestamps();
        });

        Schema::create('vehicle_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->string('photo_type', 40)->default('exterior'); // front, back, left, right, interior
            $table->string('file_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('vehicle_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('old_status', 30);
            $table->string('new_status', 30);
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_odometer_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->unsignedInteger('odometer_value');
            $table->string('reading_type', 30); // handover, return, maintenance, audit
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_odometer_readings');
        Schema::dropIfExists('vehicle_status_history');
        Schema::dropIfExists('vehicle_photos');
        Schema::dropIfExists('vehicle_documents');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('pickup_locations');
        Schema::dropIfExists('vehicle_models');
        Schema::dropIfExists('vehicle_brands');
        Schema::dropIfExists('vehicle_categories');
    }
};

