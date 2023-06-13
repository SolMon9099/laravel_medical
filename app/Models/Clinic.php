<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'clinic_adderss',
        'clinic_adderss_line2',
        'clinic_city',
        'clinic_state',
        'clinic_postal',
        'technician_id'
    ];

    public function doctors()
    {
        return $this->belongsToMany(User::class, 'clinic_doctors');
    }
}
