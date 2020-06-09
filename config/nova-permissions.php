<?php

return [
    /*
    |--------------------------------------------------------------------------
    | User model class
    |--------------------------------------------------------------------------
    */

    'user_model' => 'App\User',

    /*
    |--------------------------------------------------------------------------
    | Nova User resource tool class
    |--------------------------------------------------------------------------
    */

    'user_resource' => 'App\Nova\User',

    /*
    |--------------------------------------------------------------------------
    | The group associated with the resource
    |--------------------------------------------------------------------------
    */

    'role_resource_group' => 'Other',

    /*
    |--------------------------------------------------------------------------
    | Database table names
    |--------------------------------------------------------------------------
    | When using the "HasRoles" trait from this package, we need to know which
    | table should be used to retrieve your roles. We have chosen a basic
    | default value but you may easily change it to any table you like.
    */

    'table_names' => [
        'roles' => 'roles',

        'role_permission' => 'role_permission',

        'role_user' => 'role_user',

        'users' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Permissions
    |--------------------------------------------------------------------------
    */

    'permissions' => [
        'view link submissions' => [
            'display_name' => 'View link submissions',
            'description'  => 'Can view link submissions',
            'group'        => 'Link Submission',
        ],

//        'create link submissions' => [
//            'display_name' => 'Create link submissions',
//            'description'  => 'Can create link submissions',
//            'group'        => 'Link Submission',
//        ],

        'upload link submissions' => [
            'display_name' => 'Upload link submissions',
            'description'  => 'Can upload link submissions',
            'group'        => 'Link Submission',
        ],

        'edit link submissions' => [
            'display_name' => 'Edit link submissions',
            'description'  => 'Can edit link submissions',
            'group'        => 'Link Submission',
        ],

        'delete link submissions' => [
            'display_name' => 'Delete link submissions',
            'description'  => 'Can delete link submissions',
            'group'        => 'Link Submission',
        ],

        'view users' => [
            'display_name' => 'View users',
            'description'  => 'Can view users',
            'group'        => 'User',
        ],

        'create users' => [
            'display_name' => 'Create users',
            'description'  => 'Can create users',
            'group'        => 'User',
        ],

        'edit users' => [
            'display_name' => 'Edit users',
            'description'  => 'Can edit users',
            'group'        => 'User',
        ],

        'delete users' => [
            'display_name' => 'Delete users',
            'description'  => 'Can delete users',
            'group'        => 'User',
        ],

        'view roles' => [
            'display_name' => 'View roles',
            'description'  => 'Can view roles',
            'group'        => 'Role',
        ],

        'create roles' => [
            'display_name' => 'Create roles',
            'description'  => 'Can create roles',
            'group'        => 'Role',
        ],

        'edit roles' => [
            'display_name' => 'Edit roles',
            'description'  => 'Can edit roles',
            'group'        => 'Role',
        ],

        'delete roles' => [
            'display_name' => 'Delete roles',
            'description'  => 'Can delete roles',
            'group'        => 'Role',
        ],
    ],
];
