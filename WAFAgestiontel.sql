-- Table agent
CREATE TABLE agent (
    id_agent INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    telephone VARCHAR(20),
    poste VARCHAR(50),
    localisation VARCHAR(50) NOT NULL, -- Bureau de l'agent
    service VARCHAR(50) NOT NULL -- Service de l'agent
);

-- Table capteur (identifie le capteur RFID utilisé pour la détection)
CREATE TABLE capteur (
    id_capteur INT AUTO_INCREMENT PRIMARY KEY,
    rfid_tag VARCHAR(50) NOT NULL UNIQUE -- Tag RFID du capteur
);

-- Table telephone (avec détection RFID intégrée)
CREATE TABLE telephone (
    id_telephone INT AUTO_INCREMENT PRIMARY KEY,
    modele VARCHAR(50) NOT NULL,
    numero_inventaire VARCHAR(50) NOT NULL UNIQUE,
    rfid_tag VARCHAR(50) NOT NULL UNIQUE, -- Le tag RFID unique de chaque téléphone
    solution_mobile ENUM('licorne', 'motus') NOT NULL,
    detecte_dans_armoire BOOLEAN NOT NULL DEFAULT TRUE, -- Suivi en temps réel par le capteur RFID
    id_capteur INT NOT NULL, -- Capteur qui surveille l'armoire
    FOREIGN KEY (id_capteur) REFERENCES capteur(id_capteur)
);

-- Table attribution (historique des attributions de téléphone)
CREATE TABLE attribution (
    id_attribution INT AUTO_INCREMENT PRIMARY KEY,
    id_telephone INT NOT NULL,
    id_agent INT NOT NULL,
    attribue_par INT NOT NULL,
    date_attribution DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_retour DATETIME DEFAULT NULL,
    statut ENUM('Attribué', 'Restitué') NOT NULL,
    FOREIGN KEY (id_telephone) REFERENCES telephone(id_telephone),
    FOREIGN KEY (id_agent) REFERENCES agent(id_agent),
    FOREIGN KEY (attribue_par) REFERENCES agent(id_agent)
);
