<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PatientTransaction extends Model
{
    use HasFactory;

    public function files()
    {
        return $this->hasMany(PatientTransactionUploadedFiles::class, 'transaction_id', 'id');
    }

    public function result_files()
    {
        return $this->hasMany(PatientResultFiles::class, 'transaction_id', 'id');
    }

    public function invoice_files()
    {
        return $this->hasMany(PatientInvoiceFiles::class, 'transaction_id', 'id');
    }

    public function referral_files()
    {
        return $this->hasMany(PatientReferralFiles::class, 'transaction_id', 'id');
    }

    public function patient()
    {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }

    public function attorney()
    {
        return $this->hasOne(User::class,'id', 'attorney_id');
    }

    public function doctor()
    {
        return $this->hasOne(User::class,'id', 'doctor_id');
    }

    public function clinic_doctor()
    {
        return $this->hasOne(ClinicDoctor::class,'doctor_id', 'doctor_id');
    }

    public function schedule()
    {
        return $this->hasOne(PatientSchedule::class,'patient_transaction_id', 'id');
    }
}
