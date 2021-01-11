CREATE VIEW vFilm
AS
    SELECT 
        Media.id as 'id',
        titre,
        Media.description as 'description',
        duree,
        Media.image as 'image',
        Film.dateSortie as 'dateSortie',
        StudioAnimation.id as 'idStudio',
        StudioAnimation.nom as 'nomStudio'
    FROM Media
        INNER JOIN Film
            ON Film.idMedia = Media.id
        INNER JOIN StudioAnimation
            ON StudioAnimation.id = Media.isStudioAnimation
      
        
CREATE VIEW vSerie
AS
    SELECT 
        Media.id as 'id',
        titre,
        Media.description as 'description',
        duree,
        Media.image as 'image',
        StudioAnimation.id as 'idStudio',
        StudioAnimation.nom as 'nomStudio'
    FROM Media
        INNER JOIN Serie
            ON Serie.idMedia = Media.id
        INNER JOIN StudioAnimation
            ON StudioAnimation.id = Media.isStudioAnimation
           
  
CREATE VIEW vSaison
AS
    SELECT 
        Media.id as 'idMedia',
        titre,
        Media.description as 'description',
        duree,
        Media.image as 'image',
        num,
        nbEpisodes,
        dateSortie,
        StudioAnimation.id as 'idStudio',
        StudioAnimation.nom as 'nomStudio'
    FROM Media
        INNER JOIN Serie
            ON Serie.idMedia = Media.id
        INNER JOIN StudioAnimation
            ON StudioAnimation.id = Media.isStudioAnimation
        INNER JOIN Saison
            ON Saison.idSerie = Media.id
    
    
CREATE VIEW vUtilisateurs
AS
    SELECT *
    FROM Personne
        INNER JOIN Utilisateur 
            ON Utilisateur.idPersonne == Personne.id;


CREATE VIEW vDoubleur
AS 
    SELECT *
    FROM Personne
        INNER JOIN Doubleur 
            ON Doubleur.idPersonne = Personne.id;
            

CREATE VIEW vModerateur
AS 
    SELECT *
    FROM Personne
        INNER JOIN Utilisateur 
            ON Utilisateur.idPersonne = Personne.id;
        INNER JOIN Moderateur
            ON Moderateur.idPersonne = Utilisateur.idPersonne;
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    