<?php
require 'db.php';

$nom = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';

// ── Requête 1 : nom + types ──
$stmt = $pdo->prepare("
    SELECT p.id_pkmn, p.nom,
           GROUP_CONCAT(t.libelle SEPARATOR ' / ') AS types
    FROM pokemon p, types t, est_type et
    WHERE et.id_pkmn = p.id_pkmn
    AND t.id_type = et.id_type
    AND p.nom LIKE ?
    GROUP BY p.id_pkmn, p.nom
    ORDER BY p.id_pkmn

");
$stmt->execute(['%' . $nom . '%']);
$pokemons = $stmt->fetchAll();

foreach ($pokemons as $p) {
    // Affiche nom et types
    echo "<strong>" . htmlspecialchars($p['nom']) . "</strong>";
    echo " → " . $p['types'] . "<br>";

    // ── Requête 2 : stats du Pokémon courant ──
    $stmt2 = $pdo->prepare("
        SELECT * FROM stats WHERE id_pkmn = ?
    ");
    $stmt2->execute([$p['id_pkmn']]);  // on passe l'id du Pokémon courant
    $stats = $stmt2->fetch();     // fetch() car 1 seule ligne de stats

    if ($stats) {
        echo "PV : "      . $stats['pv']          . "<br>";
        echo "Attaque : "  . $stats['attaque']     . "<br>";
        echo "Défense : "  . $stats['defense']     . "<br>";
        echo "Vitesse : "  . $stats['vitesse']     . "<br>";
        echo "Att.Spé : "  . $stats['attaque_spe'] . "<br>";
        echo "Déf.Spé : "  . $stats['defense_spe'] . "<br>";
    } 
    else {
        echo "Pas de stats disponibles.<br>";
    }
    echo "<hr>"; // ligne de séparation entre chaque Pokémon
}