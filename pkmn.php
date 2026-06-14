<?php
require 'db.php';
require 'fonctions.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;    // on récupère l'ID dans l'URL et protection contre injection SQL

// Requête SQL pour le Pokémon sélectionné
$stmt = $pdo->prepare("
    SELECT p.*, s.pv, s.attaque, s.defense, s.attaque_spe, s.defense_spe, s.vitesse, GROUP_CONCAT(t.libelle SEPARATOR '/') AS types, et.* FROM pokemon p, stats s, types t, est_type et
    WHERE p.id_pkmn = s.id_pkmn AND p.id_pkmn = et.id_pkmn AND t.id_type = et.id_type AND p.id_pkmn = ?
");
$stmt->execute([$id]);
$pokemon = $stmt->fetch();
$sprite = 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/' . $id . '.png';
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
    <table class="table table-striped table-hover">
        <thead class="table-secondary">
            <tr>
                <th> Image </th>
                <th> Numéro de Pokédex </th>
                <th> Nom </th>
                <th> Types </th>
                <th> PV </th>
                <th> Attaque </th>
                <th> Défense </th>
                <th> Attaque spéciale </th>
                <th> Défense spéciale </th>
                <th> Vitesse </th>
            </tr>
        </thead>
        <tbody id="table">
            <tr>
                <td> 
                    <img src="<?= $sprite ?>" alt="<?= htmlspecialchars($pokemon['nom']) ?>">
                </td>
                <td> 
                        <strong> <?= htmlspecialchars($pokemon['id_pkmn']) ?> </strong>
                    </td>
                    <td>
                        <?= htmlspecialchars($pokemon['nom']) ?>
                    </td>
                    <td>
                        <?= convertTypesEnImages($pokemon['types']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($pokemon['pv']) ?> 
                    </td>
                    <td>
                        <?= htmlspecialchars($pokemon['attaque']) ?> 
                    </td>
                    <td>
                        <?= htmlspecialchars($pokemon['defense']) ?> 
                    </td>
                    <td>
                        <?= htmlspecialchars($pokemon['attaque_spe']) ?> 
                    </td>
                    <td>
                        <?= htmlspecialchars($pokemon['defense_spe']) ?> 
                    </td>
                    <td>
                        <?= htmlspecialchars($pokemon['vitesse']) ?> 
                    </td>
            </tr>
        </tbody>
    <a href="index.php"> Retour </a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>