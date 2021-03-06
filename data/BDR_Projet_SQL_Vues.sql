-- Vue contenant les medias de type film
DROP VIEW IF EXISTS vFilm;
CREATE VIEW vFilm
AS
    SELECT 
        Media.id as 'id',
        'Movie' as 'type',
        titre,
        Media.description as 'description',
        duree,
        Media.image as 'image',
        StudioAnimation.nom as 'nomStudio',
        Film.dateSortie as 'dateSortie',
        1 as 'nbSaisons',
        1 as 'nbEpisodes',
        (SELECT AVG(note) FROM utilisateur_media_note WHERE utilisateur_media_note.idMedia = Media.id) as 'score'
    FROM Media
        INNER JOIN Film
            ON Film.idMedia = Media.id
        INNER JOIN StudioAnimation
            ON StudioAnimation.id = Media.idStudioAnimation;

-- Vue contenant les medias de type Série
DROP VIEW IF EXISTS vSerie;
CREATE VIEW vSerie
AS
    SELECT 
        Media.id as 'id',
        'Serie' as 'type',
        titre,
        Media.description as 'description',
        duree,
        Media.image as 'image',
        StudioAnimation.nom as 'nomStudio',
        (SELECT dateSortie FROM Saison WHERE idSerie = Media.id ORDER BY num LIMIT 1) as 'dateSortie',
        (SELECT COUNT(num) FROM Saison WHERE idSerie = Media.id) as 'nbSaisons',
        (SELECT SUM(nbEpisodes) FROM Saison WHERE idSerie = Media.id) as 'nbEpisodes',
        (SELECT AVG(note) FROM utilisateur_media_note WHERE utilisateur_media_note.idMedia = Media.id) as 'score'
    FROM Media
        INNER JOIN Serie
            ON Serie.idMedia = Media.id
        INNER JOIN StudioAnimation
            ON StudioAnimation.id = Media.idStudioAnimation;

-- Vue contenant les utilisateurs de la plateforme
DROP VIEW IF EXISTS vUtilisateur;
CREATE VIEW vUtilisateur
AS
    SELECT *, EXISTS (SELECT * FROM moderateur WHERE Personne.id = moderateur.idPersonne) AS 'moderateur'
    FROM Personne
        INNER JOIN Utilisateur 
            ON Utilisateur.idPersonne = Personne.id;

-- Vue contenant les doubleurs
DROP VIEW IF EXISTS vDoubleur;
CREATE VIEW vDoubleur
AS 
    SELECT *
    FROM Personne
        INNER JOIN Doubleur 
            ON Doubleur.idPersonne = Personne.id;

-- Vue pour la liste des films apparaissant dans les listes des utilisateurs
DROP VIEW IF EXISTS vUtilisateur_Lists_Film;
CREATE VIEW vUtilisateur_Lists_Film
AS
SELECT DISTINCT
    Pers.id AS 'id',
    Pers.nom,
    Pers.prenom,
    User_Film.nom AS 'liste',
    NULL AS 'saison',
    Media.titre AS 'media',
    'film' AS 'categorie',
    User_Film.idMedia,
    Media.image,
    NULL AS 'nbEpisodesVus'
FROM Utilisateur_Film AS User_Film
         INNER JOIN Personne AS Pers ON Pers.id = User_Film.idPersonne
         INNER JOIN Media ON Media.id = User_Film.idMedia;

-- Vue pour la liste des séries et leurs saisons apparaissant dans les listes des utilisateurs
DROP VIEW IF EXISTS vUtilisateur_Lists_Serie;
CREATE VIEW vUtilisateur_Lists_Serie
AS
SELECT DISTINCT
    Pers.id AS 'id',
    Pers.nom,
    Pers.prenom,
    User_Sa.nom AS 'liste',
    Saison.num AS 'saison',
    Media.titre AS 'media',
    'serie' AS 'categorie',
    User_Sa.idMedia,
    Media.image,
    User_Sa.nbEpisodesVus AS 'nbEpisodesVus'
FROM Utilisateur_Saison AS User_Sa
         INNER JOIN Personne AS Pers ON Pers.id = User_Sa.idPersonne
         INNER JOIN Saison ON Saison.num = User_Sa.numSaison
         INNER JOIN Media ON Media.id = User_Sa.idMedia;