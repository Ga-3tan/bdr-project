-- Recuperes les informations d'une personne
SELECT *
FROM Personne
WHERE id = <idPersonne>;

-- Recuperer l'id de l'utilisateur en fonction de son pseudo
SELECT idPersonne
FROM Utilisateur
WHERE pseudo = '<pseudo>';

-- Recuperer lles infomrations d'un utilisateur
SELECT *
FROM vUtilisateur
WHERE pseudo = '<pseudo>';

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
SELECT password
FROM Utilisateur
WHERE pseudo = '<pseudo>';

-- Comparaison de pseudos
SELECT pseudo
FROM Utilisateur
WHERE pseudo = '<pseudo>';

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

-- Ajout d'un flm dans une liste
REPLACE INTO Utilisateur_Film
    VALUES (<idUtilisateur>, <idMedia>, '<liste>', NOW());

-- Ajout d'une serie dans une liste
REPLACE INTO Utilisateur_Saison
    VALUES (<idUtilisateur>, <idSaison>, <idMedia>, '<liste>', NOW(), <nbEpisodesVus>);

-- Recuperation d'une saison d'un media
SELECT *
FROM Saison
WHERE idSerie = <idSerie> AND num = <NoSaison>;

-- Recuperation des categories
SELECT *
FROM Categorie
ORDER BY tag;

-- Récupération des studios d'animation
SELECT *
FROM StudioAnimation
ORDER BY nom;

-- Ajouter une categorie a un media
INSERT INTO Media_Categorie
    VALUES ('<tag categorie>', <idMedia>);

-- Recuperer les categories d'un media
SELECT tagCategorie
FROM Media_Categorie
WHERE idMedia = <idMedia>;

-- Creer une nouvelle saison pour un media
INSERT INTO Saison
    VALUES (<numSaison>, <idMedia>, <nbEpisodes>, '<dateSortie>');

-- Creer une nouvelle categorie
INSERT INTO Categorie
    VALUES ('<tag>');

-- Creer un nouveau film
CALL ajouter_film('<titre>', '<description>', <duree>, '<image>', <idStudio>, '<dateSortie>', @newId);

-- Creer une nouvelle serie
CALL ajouter_serie('<titre>', '<description>', <duree>, '<image>', <idStudio>, @newId);

-- Creer un nouveau doubleur
CALL ajouter_doubleur('<nom>', '<prenom>', '<dateNaissance>', '<sexe>', '<photoProfil>');

-- Creer un nouveau studio d'animation
INSERT INTO StudioAnimation
    VALUES (NULL, '<nom>', '<description>', '<imageLogo>');

-- Ajouter un doubleur sur un media
INSERT INTO Doubleur_Media
    VALUES (<idDoubleur>, <idMedia>);

-- Ajouter un nouveau moderateur
INSERT INTO Moderateur VALUES ("<idUtilisateur>");

-- Recuperer les listes d'un utilisateur
SELECT *
FROM vUtilisateur_Lists_Film
    WHERE id = <idPersonne> AND liste = <nomListe>
UNION ALL
SELECT *
    FROM vUtilisateur_Lists_Serie
WHERE id = <idPersonne> AND liste = <nomListe>;

-- Rechercher un film ou une serie
SELECT *
FROM (
        SELECT * FROM vFilm
            WHERE titre LIKE '%" . $name . "%' AND nomStudio LIKE '%" . $studio . "%'
        UNION ALL
        SELECT * FROM vSerie
            WHERE titre LIKE '%" . $name . "%' AND nomStudio LIKE '%" . $studio . "%'
    ) res
    INNER JOIN Media_Categorie
        ON id = idMedia
        AND tagCategorie LIKE '%" . $category . "%'
GROUP BY titre
ORDER BY " . $order . " LIMIT 50;

-- Rechercher uniquement une serie
SELECT *
FROM vSerie
    INNER JOIN Media_Categorie
        ON id = idMedia
        AND tagCategorie LIKE '%" . $category . "%'
WHERE titre LIKE '%" . $name . "%'
    AND nomStudio LIKE '%" . $studio . "%'
GROUP BY titre
ORDER BY " . $order . " LIMIT 50;

-- Rechercher uniquement un film
SELECT *
FROM vFilm
    INNER JOIN Media_Categorie
        ON id = idMedia
        AND tagCategorie LIKE '%" . $category . "%'
WHERE titre LIKE '%" . $name . "%'
    AND nomStudio LIKE '%" . $studio . "%'
GROUP BY titre
ORDER BY " . $order . " LIMIT 50;