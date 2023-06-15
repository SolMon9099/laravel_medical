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
            $table->string('patient_date_injury');
            $table->text('reason_referral');
            $table->string('patient_insurance_company');
            $table->string('patient_insurance_policy');
            $table->string('patient_insurance_street_adderss');
            $table->string('patient_insurance_street_adderss_line2')->nullable();
            $table->string('patient_insurance_city');
            $table->string('patient_insurance_state');
            $table->string('patient_insurance_postal');
            $table->string('defendant_insurance_hit');
            $table->string('defendant_insure');
            $table->string('defendant_insurance_company');
            $table->string('defendant_insurance_claim');
            $table->string('defendant_policy_limit');
            $table->string('defendant_insurance_street_adderss');
            $table->string('defendant_insurance_street_adderss_line2')->nullable();
            $table->string('defendant_insurance_city');
            $table->string('defendant_insurance_state');
            $table->string('defendant_insurance_postal');
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

