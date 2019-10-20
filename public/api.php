<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/config/config.php');
require(INC.'/Database.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

try {
    $pdo = new Database();
    $result = [];
    $sql_q = 'SELECT * FROM data';

    $limit = !empty($_GET['limit']) ? (int) $_GET['limit'] ?? false : false;
    if (false !== $limit && 0 >= $limit) $limit = false;
    $sort = !empty($_GET['sort']) ? 'desc' === $_GET['sort'] ? 'desc' : 'asc' : false;

    if (!empty($_GET['date'])) {
        $sql_q.= ' WHERE created_at LIKE :created_at';
        $pdo->bind('created_at', filter_input(INPUT_GET, "date", FILTER_SANITIZE_STRING).'%');
    }
    if ($sort) $sql_q.= ' ORDER BY id '.$sort;
    if ($limit) $sql_q.= ' LIMIT '.$limit;
    
    echo json_encode($pdo->query($sql_q));
} catch (Exception $e) {
    echo json_encode(['error' => 'Bad query']);
}