<?php
require 'db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;    // on récupère l'ID dans l'URL et protection contre injection SQL

// Requête SQL pour le Pokémon sélectionné
$stmt = $pdo->prepare("
    SELECT p.*, s.pv, s.attaque, s.defense, s.attaque_spe, s.defense_spe, s.vitesse FROM pokemon p, stats s 
    WHERE p.id_pkmn = s.id_pkmn AND p.id_pkmn = ?
");
$stmt->execute([$id]);
$pokemon = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Yassine_Benmerah_&_Chahine_Choudar">
  <title><?= htmlspecialchars($pokemon['nom']) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">MyPokeDex</a>
        </div>
    </nav>
    <p> Ce Pokémon s'appelle <?= htmlspecialchars($pokemon['nom']) ?>. </p>
    <a href="index.php"> Retour </a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>