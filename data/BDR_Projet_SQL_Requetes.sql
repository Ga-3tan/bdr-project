-- Recuperer l'id de l'utilisateur en fonction de son pseudo
SELECT idPersonne FROM Utilisateur WHERE pseudo = '<pseudo>';

-- Creer un nouvel utilisateur dans la base de donnees
INSERT INTO Personne(nom, prenom)
    VALUES ('<prenom>', '<nom>');

INSERT INTO Utilisateur(email, pseudo, password, idPersonne)
VALUES ('<email>>', 'pseudo', '<mot de passe>', <id>);

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