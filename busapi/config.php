<?php
$dbHost     = 'localhost';
$dbUsername = 'u588516887_newadmin';
$dbPassword = '2+mQBKCng|$b';
$dbName     = 'u588516887_newadmin';
//Create connection and select DB


$mysqli =  new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if($mysqli->connect_error){
die("Unable to connect database: " . $mysqli->connect_error);
}

date_default_timezone_set('Asia/Kolkata');

?>

