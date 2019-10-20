<?php

return [
    'tables' => [
        # Create user table
        'CREATE TABLE IF NOT EXISTS "users" (
            "id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            "username"	VARCHAR(255) NOT NULL,
            "email"	VARCHAR(255) NOT NULL,
            "password"	TEXT NOT NULL,
            "created_at"	VARCHAR(15) NOT NULL,
            "modified_at"	VARCHAR(15) NOT NULL
        );',
        # Create options table
        'CREATE TABLE IF NOT EXISTS "options" (
            "key"	VARCHAR(50),
            "value"	VARCHAR(255)
        );',
        # Create data table
        'CREATE TABLE IF NOT EXISTS "data" (
            "id"	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            "temperature"	VARCHAR(15) NOT NULL,
            "created_at"	VARCHAR(15) NOT NULL
        );'
    ],

    'data' => [
        'users' => array(
            [
                'username' => 'admin',
                'email' => 'test@gmail.com',
                'password' => 'password'
            ]
        ),

        'options' => array(
            'refresh_time_cli' => 120,
            'refresh_time_gui' => 120,
            'max_data_to_show' => 10,
        )
    ],

    'fixtures' => [
        'data' => array(
            ['temperature' => 30.9, 'created_at' => 'now'],
            ['temperature' => 25, 'created_at' => '+0 day'],
            ['temperature' => 30.9, 'created_at' => '+1 day']
        )
    ]
    
];