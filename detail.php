<?php
include 'database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo 'Geen geldige game geselecteerd';
    exit;
}

//hiermee haal je je game op
$stmt = $pdo->prepare('SELECT * FROM games WHERE id = :id');
$stmt->bindParam('id', $id, PDO::PARAM_INT);
$stmt->execute();
$game= $stmt->fetch();

?>