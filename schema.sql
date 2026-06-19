
-- Création de la base de données
CREATE DATABASE IF NOT EXISTS pokemon_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pokemon_db;

DROP TABLE IF EXISTS types;
DROP TABLE IF EXISTS pokemon;
DROP TABLE IF EXISTS stats;
DROP TABLE IF EXISTS attaques;
DROP TABLE IF EXISTS est_type;
DROP TABLE IF EXISTS evolue_en;

-- =============================================================
-- TABLE types
-- =============================================================
CREATE TABLE types (
    id_type INT NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(20) NOT NULL,
    CONSTRAINT cle_type PRIMARY KEY (id_type),
    CONSTRAINT type_unique UNIQUE (libelle)
);

-- =============================================================
-- TABLE pokemon
-- Table centrale : tout le reste s'y rattache
-- =============================================================
CREATE TABLE pokemon (
    id_pkmn INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(30) NOT NULL,
    id_famille INT NOT NULL,    -- référencie les Pokémon qui sont dans une même famille
    CONSTRAINT cle_pokemon PRIMARY KEY (id_pkmn),
    CONSTRAINT nom_pkmn_unique UNIQUE (nom),
    CONSTRAINT id_famille_valide CHECK (id_famille > 0)        
);

-- =============================================================
-- TABLE : stats  ← RELATION 1-1 avec pokemon
-- Chaque Pokémon a EXACTEMENT une ligne de stats
-- =============================================================
CREATE TABLE stats (
    id_pkmn      INT NOT NULL,
    pv           INT NOT NULL DEFAULT 0,
    attaque      INT NOT NULL DEFAULT 0,
    defense      INT NOT NULL DEFAULT 0,
    vitesse      INT NOT NULL DEFAULT 0,
    attaque_spe  INT NOT NULL DEFAULT 0,
    defense_spe  INT NOT NULL DEFAULT 0,
    CONSTRAINT cle_stats PRIMARY KEY (id_pkmn),
    CONSTRAINT cle_etrangere_stats_pokemon
        FOREIGN KEY (id_pkmn)
        REFERENCES pokemon (id_pkmn)
        ON DELETE CASCADE  
        ON UPDATE CASCADE
);

-- =============================================================
-- TABLE : attaques  ← RELATION 1-N avec types
-- Une attaque appartient à UN seul type
-- Un type peut avoir PLUSIEURS attaques
-- =============================================================
CREATE TABLE attaques (
    id_a INT NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL,
    id_type INT NOT NULL,
    pp INT NOT NULL,
    puissance INT DEFAULT NULL,  -- NULL = attaque sans dégâts directs
    precis INT NOT NULL DEFAULT 100,
    CONSTRAINT cle_attaques PRIMARY KEY (id_a),
    CONSTRAINT cle_etrangere_attaques_type
        FOREIGN KEY (id_type)
        REFERENCES types (id_type)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT nb_pp CHECK (pp > 0),
    CONSTRAINT power CHECK (puissance >= 0)
);

-- =============================================================
-- TABLE : est_type  ← RELATION N-N entre pokemons et types
-- Un Pokémon peut avoir 1 ou 2 types
-- Un type peut appartenir à plusieurs Pokémon
-- =============================================================
CREATE TABLE est_type (
    id_pkmn INT NOT NULL,
    id_type INT NOT NULL,
    CONSTRAINT cle_est_type PRIMARY KEY (id_pkmn, id_type),   -- clé primaire COMPOSITE = pas de doublon
    CONSTRAINT cle_etrangere_est_type_pokemon
        FOREIGN KEY (id_pkmn)
        REFERENCES pokemon (id_pkmn)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT cle_etrangere_est_type_type
        FOREIGN KEY (id_type)
        REFERENCES types (id_type)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- =============================================================
-- TABLE : evolue_en  ← RELATION spéciale : pokemons → pokemons
-- Un Pokémon peut évoluer vers un autre Pokémon
-- =============================================================
CREATE TABLE evolue_en (
    id_pkmn_base INT NOT NULL,   -- le Pokémon de départ
    id_pkmn_evo INT NOT NULL,   -- le Pokémon d'arrivée
    methode VARCHAR(50) NOT NULL, -- niveau X, pierre Y, échange, bonheur,...

    CONSTRAINT cle_evolue_en PRIMARY KEY (id_pkmn_base, id_pkmn_evo),
    CONSTRAINT cle_etrangere_pkmn_base
        FOREIGN KEY (id_pkmn_base)
        REFERENCES pokemon (id_pkmn)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT cle_etrangere_pkmn_evo
        FOREIGN KEY (id_pkmn_evo)
        REFERENCES pokemon (id_pkmn)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT chk_evolution CHECK (id_pkmn_base <> id_pkmn_evo)
);