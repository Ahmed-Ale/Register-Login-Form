<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "register_login";

$conn = mysqli_connect($host,$user,$pass,$dbname);

if (!$conn) {
    die("something went wrong");
}

?>