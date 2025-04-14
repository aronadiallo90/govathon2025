-- Nettoyage
DROP TABLE IF EXISTS votes, projets_etapes, jurys, projets, secteurs, etapes;

-- Table des secteurs d'activité
CREATE TABLE secteurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

-- Table des étapes du hackathon
CREATE TABLE etapes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    ordre INT NOT NULL UNIQUE,
    date_debut DATE,
    date_fin DATE
);

-- Table des projets
CREATE TABLE projets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_equipe VARCHAR(100) NOT NULL,
    nom_projet VARCHAR(100) NOT NULL,
    etablissement VARCHAR(150), -- NULL accepté
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    secteur_id INT,
    theme VARCHAR(150),
    avancement_prototype VARCHAR(50),
    play_video_url TEXT,
    detail TEXT,
    FOREIGN KEY (secteur_id) REFERENCES secteurs(id) ON DELETE SET NULL
);

-- Table des jurys
CREATE TABLE jurys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    type ENUM('Technique', 'Business', 'Externe') NOT NULL,
    secteur_id INT, -- NULL = jury global
    FOREIGN KEY (secteur_id) REFERENCES secteurs(id) ON DELETE SET NULL
);

-- Table des participations des projets aux étapes
CREATE TABLE projets_etapes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projet_id INT NOT NULL,
    etape_id INT NOT NULL,
    est_selectionne BOOLEAN DEFAULT TRUE,
    note_finale DECIMAL(5,2),  -- moyenne des votes à cette étape
    classement INT,            -- position/rang du projet dans cette étape
    UNIQUE (projet_id, etape_id),
    FOREIGN KEY (projet_id) REFERENCES projets(id) ON DELETE CASCADE,
    FOREIGN KEY (etape_id) REFERENCES etapes(id) ON DELETE CASCADE
);

-- Table des votes
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jury_id INT NOT NULL,
    projet_id INT NOT NULL,
    etape_id INT NOT NULL,
    note DECIMAL(4,2) CHECK (note >= 0 AND note <= 10),
    commentaire TEXT,
    date_vote TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (jury_id, projet_id, etape_id),
    FOREIGN KEY (jury_id) REFERENCES jurys(id) ON DELETE CASCADE,
    FOREIGN KEY (projet_id, etape_id) REFERENCES projets_etapes(projet_id, etape_id) ON DELETE CASCADE
);
