<?php
require 'db.php';

// Fonction pour construire le path des images des types
function convertTypesEnImages($types) {
    $path = '';
    foreach (explode('/', $types) as $t) {
        $path .= '<img class="img-type" src="images/' . strtolower(trim($t)) . '.png">';
    }
    return $path;
}

// Fonction pour récupérer les informations relatives aux évolutions d'un Pokémon (sa famille) 
function recupFamille($id) {
    global $pdo;

    $cmd = "SELECT p1.id_pkmn AS id_base, p2.id_pkmn AS id_evo, p1.nom AS nom_base, p2.nom AS nom_evo, e.methode, e.prio
        FROM pokemon p1, pokemon p2, evolue_en e
        WHERE p1.id_pkmn = e.id_pkmn_base
        AND p2.id_pkmn = e.id_pkmn_evo
        AND p1.id_famille = ?
        ORDER BY e.prio ASC;";
    $requete = $pdo->prepare($cmd);

    $requete->execute([$id]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}