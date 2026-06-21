const image = document.querySelector("#main");
const titre = document.querySelector("h1");
const cheminPokemonNormal = titre.dataset.spriteNormal;     // récupère le path dans le champ défini dans la balise h1 de pkmn.php
const cheminPokemonShiny = titre.dataset.spriteShiny;       // récupère le path dans le champ défini dans la balise h1 de pkmn.php
const bling = new Audio("assets/sons/shiny.mp3");
let sh = 0;     // état du sprite. 0 = normal, 1 = shiny

window.addEventListener("load", () => {
    titre.addEventListener("click", () => {
        if (sh === 0) {
            image.src = cheminPokemonShiny;
            bling.play();
            sh = 1;
        }
        else {
            image.src = cheminPokemonNormal;
            sh = 0;
        }
    });
});
