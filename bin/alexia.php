<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/vendor/autoload.php');

$db_data = require(ROOT.'/config/database-tables.php');

$args = getopt('', ['install', 'load-fixtures']);
$config = [
    'install' => isset($args['install']),
    'fixtures' => isset($args['load-fixtures'])
];

$db_data = require(ROOT.'/config/database-tables.php');
$db = Utils::getDatabase();

if ($config['install']) {
    try {
        for ($i = 0; $i < count($db_data['tables']); $i++) {
            $db->create($db_data['tables'][$i][0], $db_data['tables'][$i][1]);
        }
        echo '[+] Tables creation: OK'.PHP_EOL;
        #-------------------
        for ($i = 0; $i < count($db_data['default']); $i++) {
            foreach ($db_data['default'][$i][1] as $item) {
                if ('users' === $db_data['default'][$i][0]) {
                    if (!empty($db->select('users', '*', ['email' => $item['email']]))) break;
                } elseif ('options' === $db_data['default'][$i][0]) {
                    if (!empty($db->select('options', '*', ['name' => $item['name']]))) break;
                }
                $db->insert($db_data['default'][$i][0], $item);
            }
        }
        echo '[+] Default data creation: OK'.PHP_EOL;
        file_put_contents(VARF.'/installed', 'ok');
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}

if ($config['fixtures']) {
    try {
        for ($i = 0; $i < count($db_data['fixtures']); $i++) {
            foreach ($db_data['fixtures'][$i][1] as $item) {
                if ('users' === $db_data['fixtures'][$i][0]) {
                    if (!empty($db->select('users', '*', ['email' => $item['email']]))) break;
                } elseif ('options' === $db_data['fixtures'][$i][0]) {
                    if (!empty($db->select('options', '*', ['name' => $item['name']]))) break;
                }
                $db->insert($db_data['fixtures'][$i][0], $item);
            }
        }
        echo '[+] Fixtures loaded: OK'.PHP_EOL;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}