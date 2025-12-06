<?php
$host = "sql.njit.edu";
$user = "sae47";
$pass = "Eghadesuwa2005@";
$db   = "sae47";  
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
