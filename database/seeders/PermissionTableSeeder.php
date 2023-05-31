<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Permissions
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',           
            'referral-list',
            'referral-create',
            'referral-edit',
            'referral-delete',           
            'clinic-list',
            'clinic-create',
            'clinic-edit',
            'clinic-delete',           
            'calendar-list',
            'calendar-create',
            'calendar-edit',
            'calendar-delete'               
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);                        
        }
    }
}
