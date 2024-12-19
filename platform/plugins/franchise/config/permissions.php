<?php

return [

    [
        'name' => 'Franchise',
        'flag' => 'plugin.franchise',
    ],
    [
        'name' => 'Franchise',
        'flag' => 'franchise.index',
        'parent_flag' => 'plugin.franchise',
    ],
    [
        'name' => 'Create',
        'flag' => 'franchise.create',
        'parent_flag' => 'franchise.create',
    ],
    [
        'name' => 'Edit',
        'flag' => 'franchise.edit',
        'parent_flag' => 'franchise.edit',
    ],
    [
        'name' => 'Delete',
        'flag' => 'franchise.destroy',
        'parent_flag' => 'franchise.destroy',
    ],
    [
        'name' => 'Franchise Services',
        'flag' => 'plugin.franchise',
    ],
    [
        'name' => 'Franchise Services',
        'flag' => 'franchise.index',
        'parent_flag' => 'plugin.franchise_service',
    ],
    [
        'name' => 'Create',
        'flag' => 'franchise_service.create',
        'parent_flag' => 'franchise_service.create',
    ],
    [
        'name' => 'Edit',
        'flag' => 'franchise_service.edit',
        'parent_flag' => 'franchise_service.edit',
    ],
    [
        'name' => 'Delete',
        'flag' => 'franchise_service.destroy',
        'parent_flag' => 'franchise_service.destroy',
    ],
    [
        'name' => 'Settings',
        'flag' => 'franchise.settings',
        'parent_flag' => 'franchise.settings',
    ],
];
