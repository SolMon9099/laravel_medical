<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patient_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('referral_date');
            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('patient_id');
            $table->string('patient_date_injury')->nullable();
            $table->text('reason_referral')->nullable();
            $table->string('patient_insurance')->nullable();
            $table->string('patient_insurance_company')->nullable();
            $table->string('patient_insurance_policy')->nullable();
            $table->string('patient_policy_limit')->nullable();
            $table->string('patient_insurance_street_adderss')->nullable();
            $table->string('patient_insurance_street_adderss_line2')->nullable();
            $table->string('patient_insurance_city')->nullable();
            $table->string('patient_insurance_state')->nullable();
            $table->string('patient_insurance_postal')->nullable();
            $table->string('defendant_insurance_hit')->nullable();
            $table->string('defendant_insure')->nullable();
            $table->string('defendant_insurance_company')->nullable();
            $table->string('defendant_insurance_claim')->nullable();
            $table->string('defendant_policy_limit')->nullable();
            $table->string('defendant_insurance_street_adderss')->nullable();
            $table->string('defendant_insurance_street_adderss_line2')->nullable();
            $table->string('defendant_insurance_city')->nullable();
            $table->string('defendant_insurance_state')->nullable();
            $table->string('defendant_insurance_postal')->nullable();
            $table->unsignedBigInteger('attorney_id');
            $table->unsignedBigInteger('doctor_id');
            $table->text('doctor_notes')->nullable();
            $table->string('status')->default(0)->comment('0:pending, 1: booked, 2:signed, 3: Test Done, 4:Advance Paid, 5:Settled');
            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('attorney_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_transactions');
    }
};

