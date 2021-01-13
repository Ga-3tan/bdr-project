-- Sélectionner tous les médias produit par un studio
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