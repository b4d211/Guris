<?php
$host = 'localhost';
$dbname = 'fastfood';
$user = 'root';
$password = 'root';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Falha na conexão: " . $conn->connect_error);
}
