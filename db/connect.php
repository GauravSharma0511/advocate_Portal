<?php
$host = "localhost";
$user = "root";
$password = "123456";
$database = "advocate_portal";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>