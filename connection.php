<?php
$host = "localhost";
$username = "root";
$password = "";
$db = "sampledb";

$conn = mysqli_connect($host, $username, $password, $db);
if (!$conn) {
    die("Connection failed: ask me" . $conn->connect_error);
    }



?> 