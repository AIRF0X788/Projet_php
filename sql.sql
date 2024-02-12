CREATE TABLE IF NOT EXISTS utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS adresses ( 
    id_adresse INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    adresse_rue VARCHAR(100),
    ville VARCHAR(50),
    etat VARCHAR(50),
    code_postal VARCHAR(10),    
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

CREATE TABLE IF NOT EXISTS produits (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS paniers (
    id_panier INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    id_produit INT,
    prix DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur),
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
);

CREATE TABLE IF NOT EXISTS commandes (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    date_commande DATE,
    prix DECIMAL(10, 2),
    nom_utilisateur VARCHAR(100),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

CREATE TABLE IF NOT EXISTS commande_produits (
    id_commande_produit INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT,
    id_produit INT,
    prix DECIMAL(10, 2),
    FOREIGN KEY (id_commande) REFERENCES commandes(id_commande),
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit)
);

CREATE TABLE IF NOT EXISTS pantalon (
    id_pantalon INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS veste (
    id_veste INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS basket (
    id_basket INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255) NOT NULL
);


CREATE INDEX IF NOT EXISTS idx_email_utilisateur ON utilisateurs (email);
CREATE INDEX IF NOT EXISTS idx_nom_produit ON produits (nom);
CREATE INDEX IF NOT EXISTS idx_prix_produit ON produits (prix);
CREATE INDEX IF NOT EXISTS idx_utilisateur_panier ON paniers (id_utilisateur);
CREATE INDEX IF NOT EXISTS idx_utilisateur_commande ON commandes (id_utilisateur);

INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe)
VALUES ('azerty', 'azerty@example.com', 'gzhgsdsh123');

INSERT INTO adresses (id_utilisateur, adresse_rue, ville, etat, code_postal)
VALUES (1, '123 Rue de Paris', 'Paris', 'Île-de-France', '75000');

INSERT INTO adresses (id_utilisateur, adresse_rue, ville, etat, code_postal)
VALUES (2, '5 Rue de antole france', 'Nanterre', 'Yvelines', '78200');

INSERT INTO adresses (id_utilisateur, adresse_rue, ville, etat, code_postal)
VALUES (3, '4 rue jean jores', 'Nantes', 'Loire-Atlantique', '44000');

INSERT INTO produits (nom, description, prix, image_url) 
VALUES ('Produit1', 'Description du Produit 1', 19.99, '../image/pantalon1.webp');

INSERT INTO produits (nom, description, prix, image_url)
VALUES ('Produit3', 'Description du Produit 3', 9.99, '../image/veste1.webp');

INSERT INTO produits (nom, description, prix, image_url)
VALUES ('Produit2', 'Description du Produit 2', 29.99, '../image/basket1.webp');

INSERT INTO paniers (id_utilisateur, id_produit, prix)
VALUES (1, 1, 19.99);

INSERT INTO commandes (id_utilisateur, date_commande, prix)
VALUES (1, '2023-10-17', 19.99);

DELETE FROM commandes
WHERE id_commande = 1;

INSERT INTO pantalon (nom, description, prix, image_url) 
VALUES ('Pantalon1', 'Couleur Bleu', 39.99, '../image/pantalon7.jpg');

INSERT INTO pantalon (nom, description, prix, image_url) 
VALUES ('Pantalon2', 'Couleur Beige', 19.99, '../image/pantalon2.jpg');

INSERT INTO pantalon (nom, description, prix, image_url) 
VALUES ('Pantalon3', 'Couleur Beige', 29.99, '../image/pantalon3.jpg');

INSERT INTO pantalon (nom, description, prix, image_url) 
VALUES ('Pantalon4', 'Couleur Gris', 29.99, '../image/pantalon4.jpg');

INSERT INTO pantalon (nom, description, prix, image_url) 
VALUES ('Pantalon5', 'Couleur Beige', 39.99, '../image/pantalon5.jpg');

INSERT INTO pantalon (nom, description, prix, image_url) 
VALUES ('Pantalon6', 'Couleur Noir', 19.99, '../image/pantalon6.jpg');

INSERT INTO veste (nom, description, prix, image_url) 
VALUES ('Veste1', 'Couleur kaki', 19.99, '../image/veste1.webp');

INSERT INTO veste (nom, description, prix, image_url) 
VALUES ('Veste2', 'Couleur Beige', 29.99, '../image/veste2.webp');

INSERT INTO veste (nom, description, prix, image_url) 
VALUES ('Veste3', 'Couleur Gris', 9.99, '../image/veste3.webp');

INSERT INTO veste (nom, description, prix, image_url) 
VALUES ('Veste4', 'Couleur Violet', 29.99, '../image/veste4.webp');

INSERT INTO veste (nom, description, prix, image_url) 
VALUES ('Veste5', 'Couleur Rouge', 19.99, '../image/veste5.webp');

INSERT INTO veste (nom, description, prix, image_url) 
VALUES ('Veste6', 'Couleur Beige', 9.99, '../image/veste6.webp');

INSERT INTO basket (nom, description, prix, image_url) 
VALUES ('Basket1', 'Couleur Grise', 79.99, '../image/basket1.webp');

INSERT INTO basket (nom, description, prix, image_url)      
VALUES ('Basket2', 'Couleur Blanche', 109.99, '../image/basket2.webp');

INSERT INTO basket (nom, description, prix, image_url) 
VALUES ('Basket3', 'Couleur Bleu & Blanche', 59.99, '../image/basket7.webp');

INSERT INTO basket (nom, description, prix, image_url) 
VALUES ('Basket4', 'Couleur Grise', 89.99, '../image/basket5.webp');

INSERT INTO basket (nom, description, prix, image_url) 
VALUES ('Basket5', 'Couleur Beige', 139.99, '../image/basket4.webp');

INSERT INTO basket (nom, description, prix, image_url) 
VALUES ('Basket6', 'Couleur Noir & Grise', 99.99, '../image/basket6.webp');
