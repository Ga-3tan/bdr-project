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

-- Ajout d'un doubleur
DROP PROCEDURE IF EXISTS ajouter_doubleur;
DELIMITER //
CREATE PROCEDURE ajouter_utilisateur (IN nom VARCHAR(50),
                                      IN prenom VARCHAR(50),
                                      IN dateNaissance DATE,
                                      IN sexe ENUM('homme', 'femme', 'autre'),
                                      IN photoProfil VARCHAR(512))

BEGIN
    INSERT INTO Personne VALUES (NULL, nom, prenom, dateNaissance, sexe, photoProfil);
    INSERT INTO Doubleur VALUES (LAST_INSERT_ID());
END//

-- Ajout d'un film

-- Ajout d'une série
