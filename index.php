<?php
require 'db.php';
require 'fonctions.php';
$nom = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';

// Requête SQL pour l'accueil
$stmt = $pdo->prepare("
    SELECT p.id_pkmn, p.nom, GROUP_CONCAT(t.libelle SEPARATOR '/') AS types, s.pv, s.attaque, s.defense, s.attaque_spe, s.defense_spe, s.vitesse
    FROM pokemon p, types t, est_type et, stats s
    WHERE et.id_pkmn = p.id_pkmn
    AND t.id_type = et.id_type
    AND s.id_pkmn = p.id_pkmn
    AND p.nom LIKE ?
    GROUP BY p.id_pkmn, p.nom, s.pv, s.attaque, s.defense, s.attaque_spe, s.defense_spe, s.vitesse
    ORDER BY p.id_pkmn
");
$stmt->execute(['%' . $nom . '%']);
$pokemons = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Yassine_Benmerah_&_Chahine_Choudar">
  <title>MyPokeDex</title>
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
        <tbody id ="core">
            <?php foreach($pokemons as $p): ?>
                <tr>
                    <td> 
                        <strong> <?= htmlspecialchars($p['id_pkmn']) ?> </strong>
                    </td>
                    <td>
                        <a href="pkmn.php?id=<?= $p['id_pkmn'] ?>"> <?= htmlspecialchars($p['nom']) ?> </a>
                    </td>
                    <td>
                        <?= convertTypesEnImages($p['types']) ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($p['pv']) ?> 
                    </td>
                    <td>
                        <?= htmlspecialchars($p['attaque']) ?> 
                    </td>
                    <td>
                        <?= htmlspecialchars($p['defense']) ?> 
                    </td>
                    <td>
                        <?= htmlspecialchars($p['attaque_spe']) ?> 
                    </td>
                    <td>
                        <?= htmlspecialchars($p['defense_spe']) ?> 
                    </td>
                    <td>
                        <?= htmlspecialchars($p['vitesse']) ?> 
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
