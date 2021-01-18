-- Ajout d'un nouvel utilisateur
DROP PROCEDURE IF EXISTS ajouter_utilisateur;
DELIMITER //
CREATE PROCEDURE ajouter_utilisateur (IN nom VARCHAR(50),
                                      IN prenom VARCHAR(50),
                                      IN dateNaissance DATE,
                                      IN sexe ENUM('homme', 'femme', 'autre'),
                                      IN photoProfil VARCHAR(512),
                                      IN email VARCHAR(256),
                                      IN pseudo VARCHAR(60),
                                      IN password VARCHAR(256))

BEGIN
    INSERT INTO Personne VALUES (NULL, nom, prenom, dateNaissance, sexe, photoProfil);
    INSERT INTO Utilisateur VALUES (email, pseudo, password, LAST_INSERT_ID());
END//
DELIMITER ;

-- Ajout d'un doubleur
DROP PROCEDURE IF EXISTS ajouter_doubleur;
DELIMITER //
CREATE PROCEDURE ajouter_doubleur (IN nom VARCHAR(50),
                                   IN prenom VARCHAR(50),
                                   IN dateNaissance DATE,
                                   IN sexe ENUM('homme', 'femme', 'autre'),
                                   IN photoProfil VARCHAR(512))

BEGIN
    REPLACE INTO Personne VALUES (NULL, nom, prenom, dateNaissance, sexe, photoProfil);
    REPLACE INTO Doubleur VALUES (LAST_INSERT_ID());
END//
DELIMITER ;

-- Ajout d'un film
DROP PROCEDURE IF EXISTS ajouter_film;
DELIMITER //
CREATE PROCEDURE ajouter_film (IN titre VARCHAR(45),
                               IN description TEXT,
                               IN duree INT,
                               IN image VARCHAR(512),
                               IN idStudioAnimation INT,
                               IN dateSortie DATE,
                               OUT newId INT)
BEGIN
    INSERT INTO Media VALUES (NULL, titre, description, duree, image, idStudioAnimation);
    SET newId = LAST_INSERT_ID();
    INSERT INTO Film VALUES (dateSortie, newId);
    SELECT newId;
END//
DELIMITER ;

-- Ajout d'une série
DROP PROCEDURE IF EXISTS ajouter_serie;
DELIMITER //
CREATE PROCEDURE ajouter_serie (IN titre VARCHAR(45),
                                IN description TEXT,
                                IN duree INT,
                                IN image VARCHAR(512),
                                IN idStudioAnimation INT,
                                OUT newId INT)
BEGIN
    INSERT INTO Media VALUES (NULL, titre, description, duree, image, idStudioAnimation);
    SET newId = LAST_INSERT_ID();
    INSERT INTO Serie VALUES (newId);
    SELECT newId;
END//
DELIMITER ;