<?php
// Fonction pour construire le path des images des types
function convertTypesEnImages($types) {
    $path = '';
    foreach (explode('/', $types) as $t) {
        $path .= '<img class="img-type" src="images/' . strtolower(trim($t)) . '.png">';
    }
    return $path;
}
?>