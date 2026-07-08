<?php

$name_host = "localhost";
$name_sql = "root";
$pass_sql = "";
$name_db = "comecode";

$condb = mysqli_connect($name_host, $name_sql, $pass_sql, $name_db);

if ($condb->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
