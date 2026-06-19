<?php
require 'db.php';
require 'fonctions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// on récupère l'ID dans l'URL et protection contre injection SQL

// Requête SQL pour le Pokémon sélectionné
$stmt = $pdo->prepare("
    SELECT
        p.*,
        s.pv,
        s.attaque,
        s.defense,
        s.attaque_spe,
        s.defense_spe,
        s.vitesse,
        GROUP_CONCAT(t.libelle SEPARATOR '/') AS types
    FROM pokemon p, stats s, types t, est_type et
    WHERE p.id_pkmn = s.id_pkmn
      AND p.id_pkmn = et.id_pkmn
      AND t.id_type = et.id_type
      AND p.id_pkmn = ?
    GROUP BY
        p.id_pkmn,
        p.nom,
        s.pv,
        s.attaque,
        s.defense,
        s.attaque_spe,
        s.defense_spe,
        s.vitesse
");

$stmt->execute([$id]);
$pokemon = $stmt->fetch();

if (!$pokemon) {
    die("Pokémon introuvable.");
}

$sprite = 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/' . $id . '.png';
    $evolutions = recupFamille($pokemon['id_famille']);
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

    <title><?= htmlspecialchars($pokemon['nom']) ?></title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    >

    <link rel="stylesheet" href="style-pkmn.css">
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                MyPokeDex
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <!-- Colonne de gauche : infos du Pokémon -->
            <div class="col-md-8">
                <table class="table table-striped table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>Image</th>
                            <th>Numéro de Pokédex</th>
                            <th>Nom</th>
                            <th>Types</th>
                            <th>PV</th>
                            <th>Attaque</th>
                            <th>Défense</th>
                            <th>Att. spé.</th>
                            <th>Déf. spé.</th>
                            <th>Vitesse</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="<?= htmlspecialchars($sprite) ?>" alt="<?= htmlspecialchars($pokemon['nom']) ?>"></td>
                            <td><strong><?= htmlspecialchars($pokemon['id_pkmn']) ?></strong></td>
                            <td><?= htmlspecialchars($pokemon['nom']) ?></td>
                            <td><?= convertTypesEnImages($pokemon['types']) ?></td>
                            <td><?= htmlspecialchars($pokemon['pv']) ?></td>
                            <td><?= htmlspecialchars($pokemon['attaque']) ?></td>
                            <td><?= htmlspecialchars($pokemon['defense']) ?></td>
                            <td><?= htmlspecialchars($pokemon['attaque_spe']) ?></td>
                            <td><?= htmlspecialchars($pokemon['defense_spe']) ?></td>
                            <td><?= htmlspecialchars($pokemon['vitesse']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Colonne de droite : évolutions -->
            <div class="col-md-4">
                <h4 class="mb-3">Évolutions</h4>
                <?php if (count($evolutions) > 0): ?>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>De</th>
                                <th>À</th>
                                <th>Méthode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evolutions as $evo): ?>
                                <tr>
                                    <td>
                                        <img class="evo-img" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?= $evo['id_base'] ?>.png" alt="">
                                        <?= htmlspecialchars($evo['nom_base']) ?>
                                    </td>
                                    <td>
                                        <img class="evo-img" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?= $evo['id_evo'] ?>.png" alt="">
                                        <?= htmlspecialchars($evo['nom_evo']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($evo['methode']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">Ce Pokémon n’évolue pas.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">Retour</a>
        </div>
    </div>            
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>