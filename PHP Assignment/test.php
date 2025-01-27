<?php
include 'db_connection.php';

if ($conn) {
    echo "Connection to the database was successful!";
} else {
    echo "Failed to connect to the database.";
}
?>
