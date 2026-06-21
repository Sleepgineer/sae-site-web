<?php
require 'db.php';
require 'fonctions.php';

// on récupère l'ID dans l'URL et protection contre injection SQL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Requête SQL pour récupérer les informations de l'attaque
$stmt = $pdo->prepare("
    SELECT att.*, t.libelle AS nom_type
    FROM attaques att, types t
    WHERE att.id_type = t.id_type
    AND att.id_a = ?;
");

$stmt->execute([$id]);
$attaque = $stmt->fetch();

if (!$attaque) {
    die("Attaque introuvable.");
}

// Requête SQL pour récupérer tous les Pokémon pouvant apprendre l'attaque
$stmt2 = $pdo->prepare("
    SELECT p.id_pkmn, p.nom, a.biais 
    FROM pokemon p, apprend a, attaques att
    WHERE p.id_pkmn = a.id_pkmn
    AND att.id_a = a.id_a
    AND att.id_a = ?
    ORDER BY p.id_pkmn ASC;
");

$stmt2->execute([$id]);
$pokemons = $stmt2->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta
        name="author"
        content="Yassine_Benmerah_&_Chahine_Choudar"
    >

    <title><?= htmlspecialchars($attaque['libelle']) ?></title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    >
    <link rel="stylesheet" href="css/style-attaque.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                MyPokéDex
            </a>
        </div>
    </nav>
    <div class="container text-center mt-4 mb-3">
        <h1 class="display-7 fw-bold text-secondary"> <?= htmlspecialchars($attaque['libelle']) ?> </h1>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Catégorie</th>
                            <th>Nombre de PP</th>
                            <th>Puissance</th>
                            <th>Précision</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= htmlspecialchars($attaque['libelle']) ?></td>
                            <td><?= convertTypesEnImages($attaque['nom_type']) ?></td>
                            <td>
                                <img src=<?='assets/images/' . strtolower(trim($attaque['categorie'])) . '.png'?> alt ="">
                            </td>
                            <td><?= htmlspecialchars($attaque['pp']) ?></td>
                            <td><?= $attaque['puissance'] !== null ? htmlspecialchars($attaque['puissance']) : '—' ?></td>
                            <td><?= $attaque['precis'] !== null ? htmlspecialchars($attaque['precis']) . '%' : '—' ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <h3 class="mb-4">Liste des Pokémons pouvant apprendre l'attaque</h3>
                <?php if (count($pokemons) > 0): ?>
                    <table class="table table-sm table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Numéro de Pokédex</th>
                                <th>Nom</th>
                                <th>Image</th>
                                <th>Obtention</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pokemons as $pkmn): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pkmn['id_pkmn']) ?></td>
                                    <td>
                                        <a href="pkmn.php?id=<?= htmlspecialchars($pkmn['id_pkmn']) ?>">
                                            <?= htmlspecialchars($pkmn['nom']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <img class="img-pkmn" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?= $pkmn['id_pkmn'] ?>.png" alt="">
                                    </td>
                                    <td><?= htmlspecialchars($pkmn['biais']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">Aucun Pokémon n'apprend cette attaque.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-1">
            <a href="index.php" class="btn btn-secondary">Retour</a>
        </div>
    </div>            
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>
</html>