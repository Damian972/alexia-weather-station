<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/config/config.php');
require(INC.'/Database.php');
require(INC.'/Utils.php');

$args = getopt('', ['install', 'load-fixtures']);
$config = [
    'install' => isset($args['install']),
    'fixtures' => isset($args['load-fixtures'])
];

$db_data = require(ROOT.'/config/database-tables.php');

$pdo = new Database();
if ($config['install']) {
    try {
        foreach ($db_data['tables'] as $table) {
            $pdo->query($table);
        }
        echo '[+] Tables creation: OK'.PHP_EOL;
        foreach ($db_data['data']['users'] as $user) {
            $user['password'] = Utils::encryptData($user['password']);
            $user['created_at'] = date('Y-m-d H:i:s');
            if (!$pdo->row('SELECT * FROM users where email = :email', ['email' => $user['email']]))
                $pdo->query('INSERT INTO users (username, email, password, created_at, modified_at) VALUES (:username, :email, :password, :created_at, :created_at)', $user);
        }
        echo '[+] Users creation: OK'.PHP_EOL;
        foreach ($db_data['data']['options'] as $k => $v) {
            $pdo->query('INSERT INTO options (key, value) VALUES (:k, :v)', compact('k', 'v'));
        }
        echo '[+] Options creation: OK'.PHP_EOL;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

if ($config['fixtures']) {
    try {
        // load fitures for data tables
        foreach ($db_data['fixtures']['data'] as $data) {            
            $pdo->query('INSERT INTO data (temperature, created_at) VALUES (:temperature, :created_at)', [
                'temperature' => $data['temperature'],
                'created_at' => date('Y-m-d H:i:s', strtotime($data['created_at']))
            ]);
        }
        echo '[+] Fixtures loaded: OK'.PHP_EOL;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

$pdo->CloseConnection();