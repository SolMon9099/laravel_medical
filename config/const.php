<?php
    return [
        'status' => [
            0 => 'Pending',
            1 => 'Booked',
            2 => 'Signed',
            3 => 'Test Done',
            4 => 'Advance Paid',
            5 => 'Settled',
        ],
        'status_code' => [
            'Pending'  => 0,
            'Booked' => 1,
            'Signed' => 2,
            'Test Done' => 3,
            'Advance Paid' => 4,
            'Settled' => 5,
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
            6 => 'technician'
        ],
        'role_codes' => [
            'office manager' => 1,
            'patient' => 2,
            'doctor' => 3,
            'attorney' => 4,
            'funding company' => 5,
            'technician' => 6
        ]
    ]
?>
