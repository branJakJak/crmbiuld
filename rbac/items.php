<?php
return [
    'admin' => [
        'type' => 1,
    ],
    'agent' => [
        'type' => 1,
    ],
    'Senior Manager' => [
        'type' => 1,
    ],
    'Manager' => [
        'type' => 1,
        'children' => [
            'managerPermission',
        ],
    ],
    'Consultant' => [
        'type' => 1,
        'children' => [
            'editOwnRecordPermission',
        ],
    ],
    'editOwnRecordPermission' => [
        'type' => 2,
        'description' => 'edit record owned by this',
        'ruleName' => 'isConsultant',
    ],
    'managerPermission' => [
        'type' => 2,
        'description' => 'manager permission',
        'ruleName' => 'isManager',
    ],
    'Admin' => [
        'type' => 1,
    ],
];
