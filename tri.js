function trier(colonne, type) {
    const tbody = document.getElementById("table");
    const lignes = Array.from(tbody.querySelectorAll("tr"));

    lignes.sort(function (ligneA, ligneB) {
        let valeurA = ligneA.children[colonne].textContent.trim();
        let valeurB = ligneB.children[colonne].textContent.trim();

        if (type === "nombre") {
            valeurA = Number(valeurA);
            valeurB = Number(valeurB);

            if (colonne === 0) {
                return valeurA - valeurB;
            } else {
                return valeurB - valeurA;
            }
        } else {
            return valeurA.localeCompare(valeurB);
        }
    });

    lignes.forEach(function (ligne) {
        tbody.appendChild(ligne);
    });
}