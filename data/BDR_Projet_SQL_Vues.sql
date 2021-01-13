DROP VIEW IF EXISTS vFilm;
CREATE VIEW vFilm
AS
    SELECT 
        Media.id as 'id',
        'Movie' as 'type',
        titre,
        Media.description as 'description',
        duree,
        Media_Categorie.tagCategorie as 'categorie',
        Media.image as 'image',
        StudioAnimation.nom as 'nomStudio',
        Film.dateSortie as 'dateSortie',
        1 as 'nbSaisons',
        1 as 'nbEpisodes'
    FROM Media
        INNER JOIN Film
            ON Film.idMedia = Media.id
        INNER JOIN StudioAnimation
            ON StudioAnimation.id = Media.idStudioAnimation
        INNER JOIN Media_Categorie
                   ON Film.idMedia = Media_Categorie.idMedia;

DROP VIEW IF EXISTS vSerie;
CREATE VIEW vSerie
AS
    SELECT 
        Media.id as 'id',
        'Serie' as 'type',
        titre,
        Media.description as 'description',
        duree,
        Media_Categorie.tagCategorie as 'categorie',
        Media.image as 'image',
        StudioAnimation.nom as 'nomStudio',
        (SELECT dateSortie FROM Saison WHERE num = Media.id ORDER BY num LIMIT 1) as 'dateSortie',
        (SELECT COUNT(num) FROM Saison WHERE num = Media.id) as 'nbSaisons',
        (SELECT SUM(nbEpisodes) FROM Saison WHERE num = Media.id) as 'nbEpisodes'
    FROM Media
        INNER JOIN Serie
            ON Serie.idMedia = Media.id
        INNER JOIN StudioAnimation
            ON StudioAnimation.id = Media.idStudioAnimation
        INNER JOIN Media_Categorie
            ON Serie.idMedia = Media_Categorie.idMedia;

DROP VIEW IF EXISTS vUtilisateur;
CREATE VIEW vUtilisateur
AS
    SELECT *
    FROM Personne
        INNER JOIN Utilisateur 
            ON Utilisateur.idPersonne = Personne.id;

DROP VIEW IF EXISTS vDoubleur;
CREATE VIEW vDoubleur
AS 
    SELECT *
    FROM Personne
        INNER JOIN Doubleur 
            ON Doubleur.idPersonne = Personne.id;

DROP VIEW IF EXISTS vModerateur;
CREATE VIEW vModerateur
AS 
    SELECT *
    FROM Personne
        INNER JOIN Utilisateur 
            ON Utilisateur.idPersonne = Personne.id;
        INNER JOIN Moderateur
            ON Moderateur.idPersonne = Utilisateur.idPersonne;
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    