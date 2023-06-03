<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PatientSchedule extends Model
{
    use HasFactory;


    public function patient()
    {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }

    public function patient_transaction(){
        return $this->hasOne(PatientTransaction::class, 'id', 'patient_transaction_id');
    }

}
