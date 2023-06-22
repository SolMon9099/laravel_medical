<?php
    return [
        'status' => [
            -1 => 'Draft',
            0 => 'Pending',
            1 => 'Booked',
            2 => 'Signed',
            3 => 'Test Done',
            4 => 'Advance Paid',
            5 => 'Settled',
        ],
        'status_code' => [
            'Draft' => -1,
            'Pending'  => 0,
            'Booked' => 1,
            'Signed' => 2,
            'Test Done' => 3,
            'Advance Paid' => 4,
            'Settled' => 5,
        ],
        'status_class' => [
            -1 => 'text-secondary',
            0 => 'text-primary',
            1 => 'text-secondary',
            2 => 'text-warning',
            3 => 'text-info',
            4 => 'text-success',
            5 => 'text-success',
        ],
        'status_bg_class' => [
            -1 => 'bg-light-secondary',
            0 => 'bg-light-primary',
            1 => 'bg-light-secondary',
            2 => 'bg-light-warning',
            3 => 'bg-light-info',
            4 => 'bg-light-success',
            5 => 'bg-light-success',
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
