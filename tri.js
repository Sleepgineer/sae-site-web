function trier(colonne, type, ordre) {
    const tbody = document.getElementById("table");
    const lignes = Array.from(tbody.querySelectorAll("tr"));

    lignes.sort(function (ligneA, ligneB) {
        let valeurA = ligneA.children[colonne].textContent.trim();
        let valeurB = ligneB.children[colonne].textContent.trim();

        if (type === "nombre") {
            valeurA = Number(valeurA);
            valeurB = Number(valeurB);

            if (ordre === "croissant") {
                return valeurA - valeurB;
            } else {
                return valeurB - valeurA;
            }
        } else {
            if (ordre === "croissant") {
                return valeurA.localeCompare(valeurB);
            } else {
                return valeurB.localeCompare(valeurA);
            }
        }
    });

    lignes.forEach(function (ligne) {
        tbody.appendChild(ligne);
    });
}