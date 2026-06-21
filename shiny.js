
const image = document.querySelector("#main");
const titre = document.querySelector("h1");
const cheminPokemonNormal = titre.dataset.spriteNormal;
const cheminPokemonShiny = titre.dataset.spriteShiny;
const bling = new Audio("shiny.mp3");
let sh = 0;

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
