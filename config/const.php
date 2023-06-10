<?php
    return [
        'status' => [
            0 => 'Pending',
            1 => 'Booked',
            2 => 'Signed',
            3 => 'Approved',
        ],
        'status_code' => [
            'Pending'  => 0,
            'Booked' => 1,
            'Signed' => 2,
            'Approved' => 3,
        ],
        'status_class' => [
            0 => 'text-primary',
            1 => 'text-info',
            2 => 'text-success',
            3 => 'text-success',
            4 => 'text-warning',
        ],
        'roles' => [
            1 => 'office manager',
            2 => 'patient',
            3 => 'doctor',
            4 => 'attorney',
            5 => 'funding company',
        ],
        'role_codes' => [
            'office manager' => 1,
            'patient' => 2,
            'doctor' => 3,
            'attorney' => 4,
            'funding company' => 5,
        ]
    ]
?>
