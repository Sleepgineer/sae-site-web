<?php
require 'db.php';
require 'fonctions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// on récupère l'ID dans l'URL et protection contre injection SQL

// Requête SQL pour le Pokémon sélectionné (infos de base et statistiques)
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
        s.vitesse;
");

$stmt->execute([$id]);
$pokemon = $stmt->fetch();  

if (!$pokemon) {
    die("Pokémon introuvable.");
}

// Requête SQL pour récupérer les attaques
$stmt2 = $pdo->prepare("
    SELECT att.id_a AS id_attaque, att.libelle, att.id_type, att.categorie, att.pp, att.puissance, att.precis, a.*, t.libelle AS nom_type
    FROM attaques att, apprend a, types t 
    WHERE a.id_a = att.id_a
    AND t.id_type = att.id_type
    AND a.id_pkmn = ?
    ORDER BY att.libelle;    
"); 

$stmt2->execute([$id]);
$attaques = $stmt2->fetchAll();

// Requête SQL pour récupérer les talents
$stmt3 = $pdo->prepare("
    SELECT ta.label, ta.detail
    FROM talent ta, possede po
    WHERE ta.label = po.label
    AND po.id_pkmn = ?
");
$stmt3->execute([$id]);
$talents = $stmt3->fetchAll();

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
    <div class="container text-center mt-4 mb-3">
        <h1 class="display-7 fw-bold text-secondary"> <?= htmlspecialchars($pokemon['nom']) ?> </h1>
    </div>
    <div class="container mt-4">
        <div class="row">
            <!-- Colonne 1 de gauche : infos du Pokémon -->
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

            <!-- Colonne 1 de droite : évolutions -->
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
                                        <a href="pkmn.php?id=<?= htmlspecialchars($evo['id_base']) ?>">
                                            <?= htmlspecialchars($evo['nom_base']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <img class="evo-img" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/<?= $evo['id_evo'] ?>.png" alt="">
                                        <a href="pkmn.php?id=<?= htmlspecialchars($evo['id_evo']) ?>">
                                            <?= htmlspecialchars($evo['nom_evo']) ?>
                                        </a>
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
        <!-- Colonne 2 de gauche : attaques -->
        <div class="row mt-4">
            <div class="col-md-8">
                <h4 class="mb-3">Attaques</h4>
                <?php if (count($attaques) > 0): ?>
                    <table class="table table-sm table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Catégorie</th>
                                <th>PP</th>
                                <th>Puissance</th>
                                <th>Précision</th>
                                <th>Obtention</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attaques as $att): ?>
                                <tr>
                                    <td>
                                        <a href="attaque.php?id=<?= htmlspecialchars($att['id_attaque']) ?>">
                                            <?= htmlspecialchars($att['libelle']) ?>
                                        </a>
                                    </td>
                                    <td><?= convertTypesEnImages($att['nom_type']) ?></td>
                                    <td>
                                        <img src=<?='images/' . strtolower(trim($att['categorie'])) . '.png'?> alt ="">
                                    </td>
                                    <td><?= htmlspecialchars($att['pp']) ?></td>
                                    <td><?= $att['puissance'] !== null ? htmlspecialchars($att['puissance']) : '—' ?></td>
                                    <td><?= $att['precis'] !== null ? htmlspecialchars($att['precis']) . '%' : '—' ?></td>
                                    <td><?= htmlspecialchars($att['biais']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">Aucune attaque enregistrée.</p>
                <?php endif; ?>
            </div>
            <!-- Colonne 2 de droite : talents -->
            <div class="col-md-4">
                <h4 class="mb-3">Talents</h4>
                <?php if (count($talents) > 0): ?>
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($talents as $tal): ?>
                                <tr>
                                    <td><?= htmlspecialchars($tal['label']) ?></td>
                                    <td><?= htmlspecialchars($tal['detail']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">Aucun talent enregistré.</p>
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