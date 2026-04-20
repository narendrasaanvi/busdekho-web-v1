<?php
header('Content-Type: application/json');

// Debug (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('config.php');

// ✅ Check DB connection
if ($mysqli->connect_error) {
    echo json_encode([
        'status' => false,
        'message' => 'Database connection failed',
        'error' => $mysqli->connect_error
    ]);
    exit;
}

// ✅ Validate input
if (!isset($_GET['bus_code']) || empty($_GET['bus_code'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Bus code is required'
    ]);
    exit;
}

$bus_code = $mysqli->real_escape_string($_GET['bus_code']);

// ✅ Fetch Bus Info
$sql = "
    SELECT 
        b.id,
        b.bus_code,
        b.bus_line,
        b.route,
        b.seat,
        b.vendor,
        
        cf.id as city_from_id,
        cf.city as city_from,
        ct.id as city_to_id,
        ct.city as city_to

    FROM tblbus b
    LEFT JOIN tblcity cf ON cf.id = b.city_from_id
    LEFT JOIN tblcity ct ON ct.id = b.city_to_id
    WHERE b.bus_code = '$bus_code'
";

$result = $mysqli->query($sql);

if (!$result) {
    echo json_encode([
        'status' => false,
        'message' => 'Query failed',
        'error' => $mysqli->error
    ]);
    exit;
}

if ($result->num_rows == 0) {
    echo json_encode([
        'status' => false,
        'message' => 'Bus not found'
    ]);
    exit;
}

$bus = $result->fetch_assoc();

// ✅ Route split
$route_points = !empty($bus['route']) ? explode('-', $bus['route']) : [];

// =============================
// ✅ Fetch Stations
// =============================
$stations = [];

$station_query = $mysqli->query("
    SELECT 
        station_id,
        station,
        arrival,
        departure,
        distance,
        day
    FROM tblstation 
    WHERE bus_code = '$bus_code'
    ORDER BY id ASC
");

if ($station_query) {
    while ($row = $station_query->fetch_assoc()) {
        $stations[] = [
            'station_id'   => $row['station_id'],
            'station_name' => $row['station'],
            'arrival'      => $row['arrival'],
            'departure'    => $row['departure'],
            'km'           => $row['distance'],
            'day'          => $row['day']
        ];
    }
}

// =============================
// ✅ Fetch Bus Types
// =============================
$bus_types = [];

$type_query = $mysqli->query("
    SELECT bustype 
    FROM tblbustype 
    WHERE bus_code = '$bus_code'
");

if ($type_query) {
    while ($row = $type_query->fetch_assoc()) {
        $bus_types[] = $row['bustype'];
    }
}

// =============================
// ✅ Final Response
// =============================
echo json_encode([
    'status' => true,
    'data' => [
        'bus_id'   => $bus['id'],
        'bus_code' => $bus['bus_code'],
        'bus_line' => $bus['bus_line'],
        'vendor'   => $bus['vendor'],
        'seat'     => $bus['seat'],

        'from' => [
            'id'   => $bus['city_from_id'],
            'name' => $bus['city_from']
        ],

        'to' => [
            'id'   => $bus['city_to_id'],
            'name' => $bus['city_to']
        ],

        'route'         => $bus['route'],
        'route_points'  => $route_points,

        // ✅ Stations List
        'stations' => $stations,

        // ✅ Bus Types
        'bus_types' => $bus_types
    ]
]);
?>