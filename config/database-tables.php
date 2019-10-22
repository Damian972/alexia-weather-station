<?php

return [
    'tables' => [
        
        # Create user table
        ['users', array(
            'id' => ('mysql' === strtolower(DB_TYPE)) ?
            [
                'INTEGER',
                'AUTO_INCREMENT',
                'NOT NULL',
                'PRIMARY KEY'
            ] : [
                'INTEGER',
                'PRIMARY KEY',
                'NOT NULL'
            ],
            'username' => [
                'VARCHAR(255)',
                'NOT NULL'
            ],
            'email' => [
                'VARCHAR(255)',
                'NOT NULL'
            ],
            'password' => [
                'VARCHAR(500)',
                'NOT NULL'
            ],
            'created_at' => [
                'VARCHAR(30)',
                'NOT NULL'
            ],
            'modified_at' => [
                'VARCHAR(30)',
                'NOT NULL'
            ]
        )],

        # Create options table
        ['options', array(
            'name' => [
                'VARCHAR(50)',
                'NOT NULL',
                'UNIQUE'
            ],
            'value' => [
                'VARCHAR(255)',
                'NOT NULL'
            ]
        )],

        # Create data table
        ['data', array(
            'id' => ('mysql' === strtolower(DB_TYPE)) ?
            [
                'INTEGER',
                'AUTO_INCREMENT',
                'NOT NULL',
                'PRIMARY KEY'
            ] : [
                'INTEGER',
                'PRIMARY KEY',
                'NOT NULL'
            ],
            'temperature' => [
                'VARCHAR(15)',
                'NOT NULL'
            ],
            'created_at' => [
                'VARCHAR(30)',
                'NOT NULL'
            ]
        )]
    ],
    
    # Default data loaded in the database
    'default'  => [
        ['users', array(
            [
                'username' => 'admin',
                'email' => 'test@gmail.com',
                'password' => Utils::encryptData('password'),
                'created_at' => date('Y-m-d H:i:s'),
                'modified_at' => date('Y-m-d H:i:s')
            ]
        )],
        ['options', array(
            ['name' => 'refresh_time_cli', 'value' => 120],
            ['name' => 'refresh_time_gui', 'value' => 120],
            ['name' => 'max_data_to_show', 'value' => 10]
        )]
    ],

    'fixtures' => [
        ['data', array(
            ['temperature' => 30.9, 'created_at' => date('Y-m-d H:i:s', strtotime('now'))],
            ['temperature' => 25, 'created_at' => date('Y-m-d H:i:s', strtotime('+0 day'))],
            ['temperature' => 30.9, 'created_at' => date('Y-m-d H:i:s', strtotime('+1 day'))]
        )]
    ]
];