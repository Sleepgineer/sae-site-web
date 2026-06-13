<?php
require 'db.php';
$nom = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';

// ── Requête 1 : nom + types ──
$stmt = $pdo->prepare("
    SELECT p.id_pkmn, p.nom, GROUP_CONCAT(t.libelle SEPARATOR ' / ') AS types, s.pv, s.attaque, s.defense, s.attaque_spe, s.defense_spe, s.vitesse
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
  <title>MyPokeDex</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <p> MyPokeDex </p>
    </header>
    <div class="container">
        <div class="row">
            <?php foreach($pokemons as $p): ?>
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <p>
                                <strong><?= htmlspecialchars($p['id_pkmn']) ?> <?= htmlspecialchars($p['nom']) ?></strong> <?= htmlspecialchars($p['types']) ?>
                                <?= htmlspecialchars($p['pv']) ?> 
                                <?= htmlspecialchars($p['attaque']) ?> 
                                <?= htmlspecialchars($p['defense']) ?> 
                                <?= htmlspecialchars($p['attaque_spe']) ?> 
                                <?= htmlspecialchars($p['defense_spe']) ?> 
                                <?= htmlspecialchars($p['vitesse']) ?> 
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
