<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_code', 30)->unique();
            $table->string('customer_type', 20)->default('individual'); // individual, corporate
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 25);
            $table->string('alternate_phone', 25)->nullable();
            $table->string('gender', 15)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->default('India');
            $table->string('id_proof_type', 30)->nullable(); // aadhaar, passport, voter_id
            $table->string('id_proof_number', 50)->nullable();
            $table->string('driving_license_number', 50)->nullable();
            $table->date('driving_license_expiry')->nullable();
            $table->boolean('is_dl_verified')->default(false);
            $table->string('status', 20)->default('active'); // active, blacklisted, inactive
            $table->text('blacklist_reason')->nullable();
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->integer('loyalty_points')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('document_type', 50); // driving_license_front, driving_license_back, aadhaar_front, aadhaar_back, passport
            $table->string('document_number', 50)->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file_path');
            $table->string('verification_status', 20)->default('pending'); // pending, verified, rejected
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('note');
            $table->boolean('is_flagged')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_notes');
        Schema::dropIfExists('customer_documents');
        Schema::dropIfExists('customers');
    }
};

