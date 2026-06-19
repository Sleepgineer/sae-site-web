<?php
require 'db.php';
require 'fonctions.php';

$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';

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

$stmt->execute(['%' . $recherche . '%']);
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
    <link rel="stylesheet" href="style-index.css">
</head>

<body>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">MyPokeDex</a>
        </div>
    </nav>

    <div class="container mt-4 mb-4">
        <form method="get" action="index.php">
            <div class="input-group">

                <input
                    type="text"
                    name="recherche"
                    class="form-control"
                    placeholder="Rechercher un Pokémon..."
                    value="<?= htmlspecialchars($recherche) ?>"
                >

                <button type="submit" class="btn btn-primary">
                    Rechercher
                </button>

                <a href="index.php" class="btn btn-secondary">
                    Réinitialiser
                </a>

            </div>
        </form>
    </div>

    <table class="table table-striped table-hover">

        <thead class="table-secondary">
            <tr>
                <th>Numéro de Pokédex</th>
                <th>Nom</th>
                <th>Types</th>
                <th>PV</th>
                <th>Attaque</th>
                <th>Défense</th>
                <th>Attaque spéciale</th>
                <th>Défense spéciale</th>
                <th>Vitesse</th>
            </tr>
        </thead>

        <tbody id="table">

            <?php foreach ($pokemons as $pokemon): ?>

                <tr>
                    <td>
                        <strong>
                            <?= htmlspecialchars($pokemon['id_pkmn']) ?>
                        </strong>
                    </td>

                    <td>
                        <a href="pkmn.php?id=<?= htmlspecialchars($pokemon['id_pkmn']) ?>">
                            <?= htmlspecialchars($pokemon['nom']) ?>
                        </a>
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

            <?php endforeach; ?>

        </tbody>
    </table>

    <?php if (count($pokemons) === 0): ?>
        <div class="container">
            <p>Aucun Pokémon trouvé.</p>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>