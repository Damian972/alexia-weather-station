<?php

define('ROOT', dirname(__FILE__, 2));

require(ROOT.'/vendor/autoload.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

try {
    $db = Utils::getDatabase();
    $result = [];
    $query_requirements = [];

    $limit = !empty($_GET['limit']) ? (int) $_GET['limit'] ?? false : false;
    if (false !== $limit && 0 >= $limit) $limit = false;
    $sort = !empty($_GET['sort']) ? 'desc' === $_GET['sort'] ? 'desc' : 'asc' : false;

    if (!empty($_GET['date'])) {
        $query_requirements['created_at[~]'] = filter_input(INPUT_GET, "date", FILTER_SANITIZE_STRING).'%';
    }
    if ($sort) $query_requirements['ORDER'] = array('id' => strtoupper($sort));
    if ($limit) $query_requirements['LIMIT'] = $limit;
    
    echo json_encode($db->select('data', ['id', 'temperature', 'created_at'], $query_requirements));
} catch (Exception $e) {
    echo json_encode(['error' => 'Bad query']);
}

/*$limit = !empty($_GET['limit']) ? (int) $_GET['limit'] ?? false : false;
    if (false !== $limit && 0 >= $limit) $limit = false;
    $sort = !empty($_GET['sort']) ? 'desc' === $_GET['sort'] ? 'desc' : 'asc' : false;

    if (!empty($_GET['date'])) {
        $sql_q.= ' WHERE created_at LIKE :created_at';
        $sql_value_to_bind[':created_at'] = filter_input(INPUT_GET, "date", FILTER_SANITIZE_STRING).'%';
    }
    if ($sort) $sql_q.= ' ORDER BY id '.$sort;
    if ($limit) $sql_q.= ' LIMIT '.$limit;*/