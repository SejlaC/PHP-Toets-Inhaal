<?php
include 'database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo 'Geen geldige game geselecteerd';
    exit;
}

//hiermee haal je je game op
$stmt = $pdo->prepare('SELECT * FROM games WHERE id = :id');

//ik geef hier met PDO::PARAM_INT aan dat $id een integer is. hierdoor weet de database dat deze parameter als getal moet worden verwerkt en niet als tekst
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$game = $stmt->fetch();


if (!$game) {
    echo 'Game is niet gevonden';
    exit;
}

//reviewformulier verwerken als het verstuurd is
$reviewError = '';
if()

//hiermee worden alle reviews voor deze specifieke game opgehaald
$stmt = $pdo->prepare('SELECT * FROM reviews WHERE game_id = :game_id');
$stmt->bindParam(':game_id', $id, PDO::PARAM_INT);
$stmt->execute();
$reviews = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detailpagina</title>
</head>
<body>
<h1><?php echo htmlspecialchars(($game['title'])); ?></h1>
<p>Genre: <?php echo htmlspecialchars($game['genre']); ?></p>
<p>Platform: <?php echo htmlspecialchars($game['platform']); ?></p>
<p>Jaar: <?php echo htmlspecialchars($game['release_year']); ?></p>

<h2>Reviews</h2>
<!--ik controleer hier of er geen reviews zijn, zodat ik een melding kan tonen-->
<?php if (empty($reviews)): ?>
    <p>Geen reviews gevonden</p>
<?php else: ?>

    <!--ik loop hier door alle reviews heen en toon voor elke review de naam, het cijfer en de opmerking-->
    <?php foreach ($reviews as $review): ?>
        <p>
            <?php echo htmlspecialchars($review['reviewer_name']); ?>
            <?php echo $review['rating']; ?>:
            <?php echo htmlspecialchars($review['comment']); ?>
        </p>
    <?php endforeach; ?>
<?php endif; ?>
<a href="index.php">Terug naar overzicht</a>

</body>
</html>
