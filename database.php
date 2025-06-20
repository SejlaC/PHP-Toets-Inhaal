<?php

$host = 'localhost';
$db = 'gamecollector';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
} catch (PDOException $e) {
    die('Verbinding mislukt: ' . $e->getMessage());
}
?>
