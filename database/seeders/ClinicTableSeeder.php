<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Clinic::create([
            'name' => 'Chicago Pain Center, LTD',
            'clinic_adderss' => '5901 Pleasant Ave',
            'clinic_adderss_line2' => '',
            'clinic_city' => 'Loves Park',
            'clinic_state' => 'IL',
            'clinic_postal' => '61111',
        ]);
    }
}

