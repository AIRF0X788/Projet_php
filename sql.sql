CREATE TABLE IF NOT EXISTS utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    statut VARCHAR(10) NOT NULL DEFAULT 'inactif',
    activation_token VARCHAR(255) DEFAULT NULL,
    est_admin BOOLEAN DEFAULT 0,
    points_fidelite INT DEFAULT 0
);
CREATE TABLE IF NOT EXISTS produits (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS panier_utilisateur (
    id_utilisateur_panier INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    nom_produit VARCHAR(100) NOT NULL,
    description_produit TEXT,
    prix_produit DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);
CREATE TABLE IF NOT EXISTS commandes (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    date_commande DATE,
    prix DECIMAL(10, 2),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

CREATE TABLE IF NOT EXISTS pantalon (
    id_pantalon INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    note_moyenne DECIMAL(2,1) DEFAULT NULL,
    category VARCHAR(255) NOT NULL
) AUTO_INCREMENT=100;

CREATE TABLE IF NOT EXISTS veste (
    id_veste INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    note_moyenne DECIMAL(2,1) DEFAULT NULL,
    category VARCHAR(255) NOT NULL
) AUTO_INCREMENT=200;

CREATE TABLE IF NOT EXISTS basket (
    id_basket INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    nom_utilisateur VARCHAR(50) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    note_moyenne DECIMAL(2,1) DEFAULT NULL,
    category VARCHAR(255) NOT NULL
) AUTO_INCREMENT=300;

CREATE TABLE IF NOT EXISTS avis_basket (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    id_produit INT,
    commentaire TEXT,
    note INT,
    nom_utilisateur VARCHAR(50) NOT NULL DEFAULT 'Utilisateur Anonyme',
    date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
    FOREIGN KEY (id_produit) REFERENCES basket(id_basket)
);


CREATE TABLE IF NOT EXISTS avis_pantalon (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    id_produit INT,
    commentaire TEXT,
    note INT,
    nom_utilisateur VARCHAR(50) NOT NULL DEFAULT 'Utilisateur Anonyme',
    date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
    FOREIGN KEY (id_produit) REFERENCES pantalon(id_pantalon)
);

CREATE TABLE IF NOT EXISTS avis_veste (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    id_produit INT,
    commentaire TEXT,
    note INT,
    nom_utilisateur VARCHAR(50) NOT NULL DEFAULT 'Utilisateur Anonyme',
    date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
    FOREIGN KEY (id_produit) REFERENCES veste(id_veste)
);

CREATE TABLE codes_promo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    valeur DECIMAL(5, 2) NOT NULL,
    actif BOOLEAN DEFAULT 0
);
CREATE TABLE IF NOT EXISTS demandes_contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    sujet VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS recompenses (
    id_recompense INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    points_necessaires INT NOT NULL
);

CREATE TABLE IF NOT EXISTS echanges_points (
    id_echange INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_recompense INT NOT NULL,
    date_echange TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
    FOREIGN KEY (id_recompense) REFERENCES recompenses(id_recompense)
);

CREATE TABLE IF NOT EXISTS recompenses_utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_recompense INT NOT NULL,
    date_attribution TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
    FOREIGN KEY (id_recompense) REFERENCES recompenses(id_recompense)
);



CREATE INDEX IF NOT EXISTS idx_email_utilisateur ON utilisateurs (email);
CREATE INDEX IF NOT EXISTS idx_nom_produit ON produits (nom);
CREATE INDEX IF NOT EXISTS idx_prix_produit ON produits (prix);
CREATE INDEX IF NOT EXISTS idx_utilisateur_commande ON commandes (id_utilisateur);
INSERT INTO codes_promo (code, valeur) VALUES ('Tom', 0.10);
INSERT INTO codes_promo (code, valeur) VALUES ('Tam', 0.20);

INSERT INTO produits (nom, description, prix, image_url, category)
VALUES (
        'Produit1',
        'Description du Produit 1',
        19.99,
        '../image/pantalon1.webp',
        'Femme'
    );
INSERT INTO produits (nom, description, prix, image_url, category)
VALUES (
        'Produit3',
        'Description du Produit 3',
        15.99,
        '../image/veste1.webp',
        'Enfant'
    );
INSERT INTO produits (nom, description, prix, image_url, category)
VALUES (
        'Produit2',
        'Description du Produit 2',
        79.99,
        '../image/basket1.webp',
        'Homme'
    );
INSERT INTO pantalon (nom, description, prix, image_url, category)
VALUES (
        'Pantalon1',
        'Couleur Bleu',
        39.99,
        '../image/pantalon7.jpg',
        'Femme'
    );
INSERT INTO pantalon (nom, description, prix, image_url, category)
VALUES (
        'Pantalon2',
        'Couleur Beige',
        19.99,
        '../image/pantalon2.jpg',
        'Enfant'
    );
INSERT INTO pantalon (nom, description, prix, image_url, category)
VALUES (
        'Pantalon3',
        'Couleur Beige',
        29.99,
        '../image/pantalon3.jpg',
        'Enfant'
    );
INSERT INTO pantalon (nom, description, prix, image_url, category)
VALUES (
        'Pantalon4',
        'Couleur Gris',
        29.99,
        '../image/pantalon4.jpg',
        'Homme'
    );
INSERT INTO pantalon (nom, description, prix, image_url, category)
VALUES (
        'Pantalon5',
        'Couleur Beige',
        39.99,
        '../image/pantalon5.jpg',
        'Femme'
    );
INSERT INTO pantalon (nom, description, prix, image_url, category)
VALUES (
        'Pantalon6',
        'Couleur Noir',
        19.99,
        '../image/pantalon6.jpg',
        'Homme'
    );
INSERT INTO veste (nom, description, prix, image_url, category)
VALUES (
        'Veste1',
        'Couleur kaki',
        15.99,
        '../image/veste1.webp',
        'Femme'
    );
INSERT INTO veste (nom, description, prix, image_url, category)
VALUES (
        'Veste2',
        'Couleur Beige',
        29.99,
        '../image/veste2.webp',
        'Enfant'
    );
INSERT INTO veste (nom, description, prix, image_url, category)
VALUES (
        'Veste3',
        'Couleur Gris',
        9.99,
        '../image/veste3.webp',
        'Enfant'
    );
INSERT INTO veste (nom, description, prix, image_url, category)
VALUES (
        'Veste4',
        'Couleur Violet',
        29.99,
        '../image/veste4.webp',
        'Homme'
    );
INSERT INTO veste (nom, description, prix, image_url, category)
VALUES (
        'Veste5',
        'Couleur Rouge',
        19.99,
        '../image/veste5.webp',
        'Femme'
    );
INSERT INTO veste (nom, description, prix, image_url, category)
VALUES (
        'Veste6',
        'Couleur Beige',
        9.99,
        '../image/veste6.webp',
        'Homme'
    );
INSERT INTO basket (nom, description, prix, image_url, category)
VALUES (
        'Basket1',
        'Couleur Grise',
        79.99,
        '../image/basket1.webp',
        'Femme'
    );
INSERT INTO basket (nom, description, prix, image_url, category)
VALUES (
        'Basket2',
        'Couleur Blanche',
        109.99,
        '../image/basket2.webp',
        'Enfant'
    );
INSERT INTO basket (nom, description, prix, image_url, category)
VALUES (
        'Basket3',
        'Couleur Bleu & Blanche',
        59.99,
        '../image/basket7.webp',
        'Enfant'
    );
INSERT INTO basket (nom, description, prix, image_url, category)
VALUES (
        'Basket4',
        'Couleur Grise',
        89.99,
        '../image/basket5.webp',
        'Homme'
    );
INSERT INTO basket (nom, description, prix, image_url, category)
VALUES (
        'Basket5',
        'Couleur Beige',
        139.99,
        '../image/basket4.webp',
        'Femme'
    );
INSERT INTO basket (nom, description, prix, image_url, category)
VALUES (
        'Basket6',
        'Couleur Noir & Grise',
        99.99,
        '../image/basket6.webp',
        'Homme'
    );

INSERT INTO echanges_points (id_utilisateur, id_recompense)
VALUES (:id_utilisateur, :id_recompense);

UPDATE utilisateurs
SET points_fidelite = points_fidelite - (SELECT points_necessaires FROM recompenses WHERE id_recompense = :id_recompense)
WHERE id_utilisateur = :id_utilisateur;
