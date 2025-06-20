<?php
include 'database.php';

//hier voer je de query in om alle games op te halen
$query = 'SELECT * FROM games';
$stmt = $pdo->prepare($query);
$stmt->execute();
$games = $stmt->fetchAll();

?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Overzicht</title>
</head>
<body>
    <h1>Overzicht</h1>
<a href="form.php">Nieuwe game toevoegen</a>
<br>

<?php if (empty($games)): ?>
<p>Er zijn nog geen games toegevoegd</p>
<?php else: ?>
<table border="1" cellpadding="1" cellspacing="1">
    <tr>
        <th>Titel</th>
        <th>Genre</th>
        <th>Platform</th>
        <th>Jaar</th>
        <th>Details</th>
    </tr>
    <?php foreach ($games as $game): ?>
    <tr>
        <td><?php echo htmlspecialchars($game['title']); ?></td>
        <td><?php echo htmlspecialchars($game['genre']); ?></td>
        <td><?php echo htmlspecialchars($game['platform']); ?></td>
        <td><?php echo htmlspecialchars($game['release_year']); ?></td>
        <td>
            <a href="detail.php?id=<?php echo $game['id']; ?>">Bekijk</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
</body>
</html>
