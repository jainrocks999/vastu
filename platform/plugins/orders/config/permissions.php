<?php

return [
    [
        'name' => 'Orders',
        'flag' => 'Orders.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'Orders.create',
        'parent_flag' => 'Orders.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'Orders.edit',
        'parent_flag' => 'Orders.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'Orders.destroy',
        'parent_flag' => 'Orders.index',
    ],
    [
        'name' => 'Orders',
        'flag' => 'Orders.settings',
        'parent_flag' => 'settings.others',
    ],
];
