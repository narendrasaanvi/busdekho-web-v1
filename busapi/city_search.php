<?php
include('config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$res = [];

// Get inputs
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$limit   = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

// Default limit safety
if ($limit <= 0) {
    $limit = 10;
}

if ($keyword != '') {

    // Search by keyword
    $keyword = $mysqli->real_escape_string($keyword);

    $query = "SELECT * FROM tblcity 
              WHERE city LIKE '%$keyword%' 
              ORDER BY city ASC 
              LIMIT $limit";

} else {

    // 🔥 IMPORTANT: when no keyword → return random cities
    $query = "SELECT * FROM tblcity 
              ORDER BY RAND() 
              LIMIT $limit";
}

$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $res[] = $row;
    }

    echo json_encode([
        'status' => true,
        'msg' => $keyword ? 'City Suggestions' : 'Random Cities',
        'count' => count($res),
        'data' => $res
    ], JSON_PRETTY_PRINT);

} else {

    echo json_encode([
        'status' => false,
        'msg' => 'No City Found',
        'count' => 0,
        'data' => []
    ], JSON_PRETTY_PRINT);
}