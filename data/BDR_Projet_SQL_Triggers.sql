-- Valide la note d'un media
DROP TRIGGER IF EXISTS before_utilisateur_media_note_insert;
DELIMITER $$
CREATE TRIGGER before_utilisateur_media_note_insert
    BEFORE INSERT
    ON utilisateur_media_note
    FOR EACH ROW
BEGIN
    IF NEW.note NOT BETWEEN 1 AND 10 THEN
        SET @s = '[table:utilisateur_media_note] - [note] column is not valid (must be between 1 and 10)';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @s;
    END IF;
END;
$$
DELIMITER ;

-- Evite un cas d'overlapping a l'insertion dans Film
DROP TRIGGER IF EXISTS before_film_insert;
DELIMITER $$
CREATE TRIGGER before_film_insert
    BEFORE INSERT
    ON film
    FOR EACH ROW
BEGIN
    IF EXISTS (SELECT * FROM Serie
                    WHERE Serie.idMedia = NEW.idMedia)
    THEN
        SET @s = '[table:film] - New film overlapping with existing serie';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @s;
    END IF;
END;
$$
DELIMITER ;

-- Evite un cas d'overlapping a l'insertion dans Serie
DROP TRIGGER IF EXISTS before_serie_insert;
DELIMITER $$
CREATE TRIGGER before_serie_insert
    BEFORE INSERT
    ON serie
    FOR EACH ROW
BEGIN
    IF EXISTS (SELECT * FROM Film
                    WHERE Film.idMedia = NEW.idMedia)
    THEN
        SET @s = '[table:serie] - New serie overlapping with existing film';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @s;
    END IF;
END;
$$
DELIMITER ;

-- Verification dates de sortie des saisons
DROP TRIGGER IF EXISTS before_saison_insert;
DELIMITER $$
CREATE TRIGGER before_saison_insert
    BEFORE INSERT
    ON saison
    FOR EACH ROW
BEGIN
    IF EXISTS (SELECT * FROM Saison
                    WHERE Saison.idSerie = NEW.idSerie
                      AND Saison.num < NEW.num
                      AND Saison.dateSortie > NEW.dateSortie)
    THEN
        SET @s = '[table:saison] - New season cant be released before the old ones';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @s;
    END IF;
END;
$$
DELIMITER ;

-- lors de l'ajout d'une list / replace
DROP TRIGGER IF EXISTS before_utilisateur_saison_insert;
DELIMITER $$
CREATE TRIGGER before_utilisateur_saison_insert
    BEFORE INSERT ON Utilisateur_Saison
    FOR EACH ROW
BEGIN
    DECLARE saisonNbEpisodes INT;
    SELECT nbEpisodes INTO saisonNbEpisodes FROM Saison WHERE NEW.idMedia = Saison.idSerie AND NEW.numSaison = Saison.num;
    IF NEW.nbEpisodesVus > saisonNbEpisodes THEN                                         -- on ne peut pas avoir vu plus d'episode que le max d'episode de la saison
        SET @s = '[table:utilisateur_saison] - [nbEpisodesVus] cannot watch more episodes than the maximum';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @s;
    ELSEIF NEW.nbEpisodesVus > 0 AND NEW.nom = 'Plan to watch' THEN                     -- si un episode a été vu, il ne peut pas être dans plan to watch
        SET @s = '[table:utilisateur_saison] - [nom] The season cannot be inside Plan to watch if one episodes has been watched';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @s;
    ELSEIF NEW.nbEpisodesVus < saisonNbEpisodes AND NEW.nom = 'Finished' THEN             -- la saison peut pas être dans finished si on a pas vu tous les episodes
        SET @s = '[table:utilisateur_saison] - [nom] The season cannot be inside Finished if all episodes arent watched';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @s;
    ELSEIF NEW.nbEpisodesVus = saisonNbEpisodes AND NEW.nom != 'Finished' THEN             -- la saison ne peut pas être ailleurs que dans Finished si on a vu tous les episodes
        SET @s = '[table:utilisateur_saison] - [nom] The season must be inside Finished if all episodes are watched';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @s;
    ELSEIF NEW.nom != 'Plan to watch' AND NOW() < (SELECT dateSortie FROM Saison WHERE Saison.idSerie = NEW.idMedia AND saison.num = NEW.numSaison) THEN
        SET @t = '[table:utilisateur_saison] - [nom] The season is not out yet, cannot put it in this list';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @t;
    END IF;
END;
$$
DELIMITER ;