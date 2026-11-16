<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 40)->unique(); // self_drive, chauffeur_driven, airport_transfer, outstation, subscription
            $table->text('description')->nullable();
            $table->boolean('is_chauffeur_included')->default(false);
            $table->boolean('requires_dl')->default(true);
            $table->decimal('gst_rate_percent', 5, 2)->default(18.00); // 18% for self-drive, 5%/12% for chauffeur
            $table->string('sac_code', 20)->default('997311'); // 997311 without operator, 996601 with operator
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('rental_rate_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('vehicle_categories')->cascadeOnDelete();
            $table->foreignId('rental_type_id')->constrained('rental_types')->cascadeOnDelete();
            $table->string('plan_name');
            $table->string('billing_cycle', 30)->default('daily'); // hourly, daily, weekly, monthly
            $table->decimal('base_rate', 10, 2);
            $table->integer('free_km_per_unit')->default(250);
            $table->decimal('excess_km_rate', 8, 2)->default(12.00);
            $table->decimal('excess_hour_rate', 8, 2)->default(150.00);
            $table->decimal('security_deposit_amount', 10, 2)->default(5000.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['company_id', 'category_id', 'rental_type_id', 'billing_cycle'], 'company_cat_type_cycle_unique');
        });

        Schema::create('rental_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('vehicle_categories')->cascadeOnDelete();
            $table->string('name'); // "8 Hr / 80 Km Local Package", "Outstation 250km/Day"
            $table->string('package_type', 40)->default('local'); // local, outstation, airport
            $table->integer('duration_hours')->default(8);
            $table->integer('included_km')->default(80);
            $table->decimal('base_price', 10, 2);
            $table->decimal('extra_km_price', 8, 2)->default(14.00);
            $table->decimal('extra_hour_price', 8, 2)->default(150.00);
            $table->boolean('driver_bata_included')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('seasonal_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('surcharge_percentage', 5, 2)->default(15.00); // e.g. +15% for peak holidays
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('promotional_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('code', 30)->unique();
            $table->string('discount_type', 20)->default('percentage'); // percentage, fixed
            $table->decimal('discount_value', 8, 2);
            $table->decimal('min_spend', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->date('valid_from');
            $table->date('valid_until');
            $table->integer('usage_limit')->default(100);
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotional_coupons');
        Schema::dropIfExists('seasonal_rates');
        Schema::dropIfExists('rental_packages');
        Schema::dropIfExists('rental_rate_plans');
        Schema::dropIfExists('rental_types');
    }
};

