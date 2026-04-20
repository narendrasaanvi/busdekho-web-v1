<?php
include('config.php');

$res = [];

$buss = $mysqli->query("SELECT * FROM tblcity ORDER BY city ASC");

if ($buss && $buss->num_rows > 0) {

    while ($row = $buss->fetch_assoc()) {
        $res[] = $row;
    }

    echo json_encode([
        'status' => true,
        'msg' => 'City List',
        'data' => $res
    ], JSON_PRETTY_PRINT);

} else {

    echo json_encode([
        'status' => false,
        'msg' => 'No Data Found',
        'data' => []
    ], JSON_PRETTY_PRINT);
}
?>