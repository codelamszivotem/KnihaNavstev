<?php
$host = 'localhost';
$user = 'belatkao';
$password = 'Maninek151*';
$dbname = 'belatkao_dyn2';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Chyba p�ipojen�: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
