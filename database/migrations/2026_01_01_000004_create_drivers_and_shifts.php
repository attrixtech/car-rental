<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('driver_code', 30)->unique();
            $table->string('name');
            $table->string('phone', 25)->unique();
            $table->string('alternate_phone', 25)->nullable();
            $table->string('license_number', 50)->unique();
            $table->string('license_type', 30)->default('commercial'); // commercial, transport, lmv
            $table->date('license_expiry_date')->index();
            $table->string('badge_number', 50)->nullable();
            $table->date('badge_expiry_date')->nullable();
            $table->string('police_verification_status', 30)->default('verified'); // verified, pending, rejected
            $table->date('medical_fitness_date')->nullable();
            $table->decimal('daily_bata_rate', 8, 2)->default(300.00); // Standard food/day bata
            $table->decimal('night_bata_rate', 8, 2)->default(300.00); // Night stay / halt bata
            $table->decimal('overtime_hourly_rate', 8, 2)->default(60.00);
            $table->decimal('monthly_salary', 10, 2)->default(0.00);
            $table->string('employment_type', 30)->default('company_payroll'); // company_payroll, contract, attached
            $table->string('status', 30)->default('available')->index(); // available, on_trip, on_leave, suspended, inactive
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->unsignedInteger('total_trips')->default(0);
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('driver_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();
            $table->string('document_type', 50); // license, badge, police_verification, medical, aadhaar
            $table->string('document_number', 50)->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::create('driver_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('shift_name', 50);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_night_shift')->default(false);
            $table->timestamps();
        });

        Schema::create('driver_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();
            $table->date('attendance_date')->index();
            $table->string('status', 30)->default('present'); // present, absent, on_trip, half_day, leave
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['driver_id', 'attendance_date']);
        });

        Schema::create('driver_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->string('allowance_type', 40); // day_bata, night_bata, overtime, outstation_stay, toll_reimbursement
            $table->decimal('amount', 8, 2);
            $table->date('duty_date');
            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_allowances');
        Schema::dropIfExists('driver_attendance');
        Schema::dropIfExists('driver_shifts');
        Schema::dropIfExists('driver_documents');
        Schema::dropIfExists('drivers');
    }
};

