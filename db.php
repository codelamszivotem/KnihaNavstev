<?php
$host = 'localhost';
$user = 'belatkao';
$password = 'Maninek151*';
$dbname = 'belatkao_dyn2';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Chyba pøipojení: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
