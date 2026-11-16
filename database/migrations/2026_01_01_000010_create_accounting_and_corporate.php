<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corporate_clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone', 25);
            $table->string('gstin', 20)->nullable();
            $table->string('pan', 15)->nullable();
            $table->text('billing_address')->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->decimal('credit_limit', 12, 2)->default(100000.00);
            $table->string('billing_cycle', 30)->default('monthly'); // monthly, fortnightly
            $table->integer('payment_terms_days')->default(30);
            $table->string('status', 20)->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('corporate_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('corporate_client_id')->constrained('corporate_clients')->cascadeOnDelete();
            $table->string('contract_number', 50)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('discount_rate_percent', 5, 2)->default(10.00);
            $table->text('terms_notes')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('account_code', 20);
            $table->string('account_name', 100);
            $table->string('account_type', 30); // asset, liability, equity, revenue, expense
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_system')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['company_id', 'account_code']);
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('entry_number', 40)->unique();
            $table->date('entry_date');
            $table->string('reference_type', 50)->nullable(); // invoice, payment, deposit, maintenance
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('narration')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained('journal_entries')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('chart_of_accounts')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->decimal('debit', 12, 2)->default(0.00);
            $table->decimal('credit', 12, 2)->default(0.00);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_cost_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->date('entry_date')->index();
            $table->string('cost_type', 40); // revenue, maintenance, fuel, insurance, emi, cleaning, permit, depreciation
            $table->decimal('amount', 12, 2);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('vehicle_gps_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->string('imei_number', 50)->unique();
            $table->string('sim_number', 30)->nullable();
            $table->string('device_model', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('vehicle_location_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('speed_kmh', 6, 2)->default(0.00);
            $table->boolean('ignition_on')->default(true);
            $table->string('address')->nullable();
            $table->timestamp('recorded_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_location_logs');
        Schema::dropIfExists('vehicle_gps_devices');
        Schema::dropIfExists('vehicle_cost_ledgers');
        Schema::dropIfExists('journal_entry_lines');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('corporate_contracts');
        Schema::dropIfExists('corporate_clients');
    }
};

