<?php
// Server name
$serverName = "ELITEBOOK\\SQLEXPRESS"; 

// Connection options
$connectionInfo = array(
    "Database" => "CollegeDB",
    "UID" => "StudentRegistrationUser", // Replace with your actual login name
    "PWD" => "mKioko" // Replace with your actual password
);

// Establish the connection
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>