<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->string('maintenance_type', 40)->default('periodic_service'); // periodic_service, breakdown_repair, body_paint, tyre_replacement, battery_replacement
            $table->unsignedInteger('scheduled_at_odometer')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->string('status', 30)->default('scheduled')->index(); // scheduled, in_progress, completed, cancelled
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('maintenance_work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('maintenance_id')->nullable()->constrained('vehicle_maintenance')->nullOnDelete();
            $table->string('work_order_number', 40)->unique();
            $table->string('vendor_name', 100);
            $table->string('vendor_phone', 25)->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('completion_datetime')->nullable();
            $table->decimal('labor_cost', 10, 2)->default(0.00);
            $table->decimal('parts_cost', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('total_cost', 10, 2)->default(0.00);
            $table->string('status', 30)->default('open'); // open, in_progress, completed, cancelled
            $table->text('technician_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('insurance_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->string('policy_number', 60)->unique();
            $table->string('insurance_company', 100);
            $table->string('policy_type', 40)->default('comprehensive'); // comprehensive, zero_dep, third_party
            $table->decimal('idv_amount', 12, 2)->default(0.00); // Insured Declared Value
            $table->decimal('premium_amount', 10, 2)->default(0.00);
            $table->date('start_date');
            $table->date('expiry_date')->index();
            $table->string('document_file')->nullable();
            $table->string('status', 20)->default('active'); // active, expired, renewed
            $table->timestamps();
        });

        Schema::create('insurance_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('policy_id')->constrained('insurance_policies')->cascadeOnDelete();
            $table->string('claim_number', 50)->unique();
            $table->date('incident_date');
            $table->text('incident_description');
            $table->decimal('claim_amount', 12, 2);
            $table->decimal('approved_amount', 12, 2)->default(0.00);
            $table->string('claim_status', 30)->default('submitted'); // submitted, under_survey, approved, rejected, settled
            $table->date('settlement_date')->nullable();
            $table->text('surveyor_remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_claims');
        Schema::dropIfExists('insurance_policies');
        Schema::dropIfExists('maintenance_work_orders');
        Schema::dropIfExists('vehicle_maintenance');
    }
};

