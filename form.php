<?php
include 'database.php';

//hier sla je de errors op
$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //validatie en inputbeveiliging
    $title = filter_input(INPUT_POST,'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $platform = filter_input(INPUT_POST,'platform', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $release_year = filter_input(INPUT_POST,'release_year', FILTER_VALIDATE_INT);

    //hier controleer je of alle velden zijn ingevuld,
    if ($title && $genre && $platform && $release_year) {
        $insert = "INSERT INTO games (title, genre, platform, release_year) VALUES (:title, :genre, :platform, :release_year)";
        $stmt = $pdo->prepare($insert);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':platform', $platform);
        $stmt->bindParam(':release_year', $release_year, PDO::PARAM_INT);

    }
}

?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Game toevoegen</title>
</head>
<body>
<form action="form.php" method="POST">
    <label>Titel:
        <input type="text" name="title" required>
    </label><br>
    <label>Genre:
        <input type="text" name="genre" required>
    </label><br>
    <label>Platform:
        <input type="text" name="platform" required>
    </label><br>
    <label>Jaar van uitgave:
        <input type="number" name="release_year" min="1970" max="2099" required>
    </label><br>
    <input type="submit" value="Game toevoegen">
</form>

</body>
</html>
