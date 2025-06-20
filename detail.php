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
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewer = filter_input(INPUT_POST, 'reviewer', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($reviewer && $rating && $comment) {
        $stmt = $pdo->prepare("INSERT INTO reviews (game_id, reviewer_name, rating, comment) VALUES (:game_id, :reviewer, :rating, :comment)");
        $stmt->bindParam(':game_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':reviewer', $reviewer);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment);

        if ($stmt->execute()) {
            //ik stuur nu door naar de detailpagina met een extra parameter 'review=succes' in de url, zodat ik straks een succesmelding kan tonen als een review is toegevoegd (ik heb het eerst met alleen 'id' gedaan
            header("Location: detail.php?id=$id&review=succes");
            exit;
        } else {
            $reviewError = 'Er is iets fout gegaan bij het toevoegen van de review.';
        }
    } else {
        $reviewError = 'Vul alle velden in';
    }
}

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
<!-- met deze code laat ik de titel, het genre, het platform en het jaar van de geselecteerde game zien -->
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

<h2>Review toevoegen</h2>
<?php
//als er een fout optreedt bij het toevoegen van een review, wordt de foutmelding hier getoond met de kleur rood
if (!empty($reviewError)) {
    echo "<p style='color:red;'>$reviewError</p>";
}
?>
<form method="post" action="">
    Naam: <input type="text" name="reviewer" required><br>
    Beoordeling (1-5): <input type="number" name="rating" min="1" max="5" required><br>
    Opmerking:<br>
    <textarea name="comment" required></textarea><br>
    <input type="submit" value="Review toevoegen">
</form>


<a href="index.php">Terug naar overzicht</a>

</body>
</html>
