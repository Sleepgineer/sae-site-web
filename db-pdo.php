<?php
// NE PAS OUBLIER DE RENOMMER EN db.php APRÈS LES MODIFICATIONS UTILISATEUR !!!
$host   = "localhost";
$dbname = "pokemon_db";
$user   = "nom_utilisateur";    // à modifier pour l'utilisateur
$pass   = "mot_de_passe";       // à modifier pour l'utilisateur
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Erreur de connexion : " . $e->getMessage());
}
 
