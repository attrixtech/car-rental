<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('deposit_number', 40)->unique();
            $table->decimal('deposit_amount', 10, 2);
            $table->string('payment_method', 30)->default('upi'); // cash, card, upi, bank_transfer
            $table->string('transaction_reference', 100)->nullable();
            $table->string('status', 30)->default('held'); // held, partially_deducted, refunded, forfeited
            $table->decimal('deductions_amount', 10, 2)->default(0.00);
            $table->decimal('refunded_amount', 10, 2)->default(0.00);
            $table->timestamp('refunded_at')->nullable();
            $table->string('refund_reference', 100)->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposit_id')->constrained('security_deposits')->cascadeOnDelete();
            $table->string('transaction_type', 30); // collection, damage_deduction, fuel_deduction, excess_km_deduction, refund
            $table->decimal('amount', 10, 2);
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('invoice_number', 40)->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('place_of_supply', 100)->default('State Hub');
            $table->string('customer_gstin', 20)->nullable();
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('taxable_amount', 12, 2)->default(0.00);
            $table->decimal('cgst_rate', 5, 2)->default(9.00);
            $table->decimal('cgst_amount', 10, 2)->default(0.00);
            $table->decimal('sgst_rate', 5, 2)->default(9.00);
            $table->decimal('sgst_amount', 10, 2)->default(0.00);
            $table->decimal('igst_rate', 5, 2)->default(0.00);
            $table->decimal('igst_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->decimal('balance_due', 12, 2)->default(0.00);
            $table->string('payment_status', 30)->default('unpaid')->index(); // unpaid, partially_paid, paid, refunded, void
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('item_description');
            $table->string('sac_code', 20)->default('997311');
            $table->decimal('quantity', 8, 2)->default(1.00);
            $table->decimal('unit_rate', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('tax_rate', 5, 2)->default(18.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('payment_number', 40)->unique();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 30)->default('upi'); // cash, card, upi, net_banking, deposit_adjustment
            $table->string('transaction_reference', 100)->nullable();
            $table->string('status', 30)->default('successful'); // successful, pending, failed, refunded
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('refund_number', 40)->unique();
            $table->decimal('amount', 10, 2);
            $table->string('refund_method', 30)->default('upi');
            $table->string('refund_reference', 100)->nullable();
            $table->text('reason')->nullable();
            $table->string('status', 30)->default('processed');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('deposit_transactions');
        Schema::dropIfExists('security_deposits');
    }
};

