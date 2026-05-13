<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "tulay_ugnayan";

$conn = new mysqli($host, $user, $password, $database); //create MySQLi connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>