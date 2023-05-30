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

   
}
