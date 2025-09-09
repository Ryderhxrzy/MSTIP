<?php
    $database = 'localhost';
    $user = 'root';
    $password = '';
    $db_name = 'MSTIP';

    $conn = mysqli_connect($database, $user, $password, $db_name);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>