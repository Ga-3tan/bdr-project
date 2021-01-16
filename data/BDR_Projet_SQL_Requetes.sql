-- Recuperes les informations d'une personne
SELECT * FROM Personne WHERE id = <idPersonne>;

-- Recuperer l'id de l'utilisateur en fonction de son pseudo
SELECT idPersonne FROM Utilisateur WHERE pseudo = '<pseudo>';

-- Creer un nouvel utilisateur dans la base de donnees
CALL ajouter_utilisateur('<nom>', '<prenom>', '<dateNaissance>', '<sexe>', '<photoProfil>', '<email>', '<pseudo>', '<password>');

-- Mettre a jou un utilisateur existant
UPDATE Personne SET
            nom = '<pseudo>',
            prenom = '<prenom>',
            dateNaissance = '<dateNaissance>',
            sexe = '<sexe>',
            photoProfil = '<photoProfil>'
WHERE id = <idPersonne>;

UPDATE Utilisateur SET
           email = '<email>',
           pseudo = '<pseudo>'
WHERE idPersonne = <idPersonne>;

-- Verification de l'utilisateur
SELECT password FROM Utilisateur WHERE pseudo = '<pseudo>';

-- Comparaison de pseudos
SELECT pseudo FROM Utilisateur WHERE pseudo = '<pseudo>';

-- Recuperation des donnes d'un media
SELECT * FROM vFilm WHERE id = <idMedia>
UNION
SELECT * FROM vSerie WHERE id = <idMedia>;

-- Recuperation de la note attribuee par un utilisateur sur un media
SELECT note, dateNote
FROM utilisateur_media_note
WHERE idPersonne = <idUtilisateur> AND idMedia = <idMedia>;

-- Recuperation de la note moyenne d'un media
SELECT AVG(note) AS 'moyenne'
FROM utilisateur_media_note
WHERE idMedia = <idMedia>;

-- Recuperation des doubleurs d'un media
SELECT id, nom, prenom, dateNaissance, sexe, photoProfil
FROM vDoubleur
    INNER JOIN Doubleur_Media
        ON vDoubleur.id = Doubleur_Media.idPersonne
WHERE Doubleur_Media.idMedia = <idMedia>;

-- Recuperation des commentaires d'un media
SELECT pseudo, commentaire, dateAjout
FROM vUtilisateur
    INNER JOIN Utilisateur_Media_Commentaire
        ON id = Utilisateur_Media_Commentaire.idPersonne
WHERE idMedia = <idMedia>;

-- Ajout d'un commentaire sur un media
INSERT INTO Utilisateur_Media_Commentaire
    VALUES (<idUtilisateur>, <idMedia>, NOW(), '<commentaire>');

-- Ajout d'une note sur un media
REPLACE INTO Utilisateur_Media_Note
    VALUES (<idUtilisateur>, <idMedia>, <note>, NOW());

-- Ajout d'un media dans une liste
REPLACE INTO Utilisateur_Film
    VALUES (<idUtilisateur>, <idMedia>, '<liste>', NOW());

REPLACE INTO Utilisateur_Saison
    VALUES (<idUtilisateur>, <idSaison>, <idMedia>, '<liste>', NOW(), <nbEpisodesVus>);

-- Recuperation d'une saison d'un media
SELECT * FROM Saison WHERE idSerie = <idSerie> AND num = <NoSaison>;

-- Recuperation des categories
SELECT * FROM Categorie ORDER BY tag;

-- Récupération des studios d'animation
SELECT * FROM StudioAnimation ORDER BY nom;

-- Ajouter une categorie a un media
INSERT INTO Media_Categorie VALUES ('<tag categorie>', <idMedia>);

-- Recuperer les categories d'un media
SELECT tagCategorie FROM Media_Categorie WHERE idMedia = <idMedia>;

-- Creer une nouvelle saison pour un media
INSERT INTO Saison VALUES (<numSaison>, <idMedia>, <nbEpisodes>, '<dateSortie>');

-- Creer une nouvelle categorie
INSERT INTO Categorie VALUES ('<tag>');

-- Creer un nouveau film
CALL ajouter_film('<titre>', '<description>', <duree>, '<image>', <idStudio>, '<dateSortie>', @newId);

-- Creer une nouvelle serie
CALL ajouter_serie('<titre>', '<description>', <duree>, '<image>', <idStudio>, @newId);

-- Creer un nouveau doubleur
CALL ajouter_doubleur('<nom>', '<prenom>', '<dateNaissance>', '<sexe>', '<photoProfil>');

-- Ajouter un doubleur sur un media
INSERT INTO Doubleur_Media VALUES (<idDoubleur>, <idMedia>);

-- Recuperer les listes d'un utilisateur
SELECT *
FROM vUtilisateur_Lists_Film
WHERE id = <idPersonne> AND liste = <nomListe>
UNION ALL
SELECT *
FROM vUtilisateur_Lists_Serie
WHERE id = <idPersonne> AND liste = <nomListe>;

-----------------------------NON UTILISE----------------------------------


-- Sélectionner tous les médias produits par un studio
SELECT *
FROM Media
   INNER JOIN StudioAnimation ON Media.idStudioAnimation = StudioAnimation.id
WHERE Studio.nom = "<Nom Studio>";

-- Sélectionner la liste des films
SELECT Media.*, Film.dateSortie
FROM Media
   INNER JOIN Film ON film.idMedia = Media.id


--Sélectionner la liste des Séries
SELECT *
FROM Media
   INNER JOIN Serie ON Serie.idMedia = Media.id


-- Sélectionner les listes des médias ayant une catégorie donnée
SELECT *
FROM Media
   INNER JOIN Media_Categorie ON Media.id = Media_Categorie.id
   -- INNER JOIN Categorie ON Categorie.tag = Media_Categorie.tagCategorie
WHERE Media_Categorie.tagCategorie = "<Nom Catégorie>"


-- Trouver tous les medias ayant un titre donné
SELECT *
FROM Media
WHERE titre LIKE "%<Titre du media>%";

--moyenne des notes
SELECT AVG(note) AS 'moyenne' FROM utilisateur_media_note WHERE idMedia = <id>;
        

-- Sélectionner la liste des média ayant un doubleur donné
SELECT *
FROM Media
   INNER JOIN vDoubleur ON Media.id = vDoubleur.id
WHERE vDoubleur.nom = "<Nom doubleur>";

-- Liste de films qui appartiennent à un utilisateur
SELECT *
FROM vUtilisateur_Lists_Film
WHERE id = <userid>;

-- Liste de séries qui appartiennent à un utilisateur
SELECT *
FROM vUtilisateur_Lists_Serie
WHERE id = <userid>;

SELECT vuf.media, vuf.liste, vuf.image
FROM vUtilisateur_Lists_Film AS vuf
WHERE id = 41
UNION
SELECT vus.media, vus.liste, vus.image
FROM vUtilisateur_Lists_Serie AS vus
WHERE id = 41;