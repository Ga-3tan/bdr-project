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

-- Verifcation de l'ajout dans les listes d'un série qui n'est pas encore sortie
DROP TRIGGER IF EXISTS before_list_insert;
DELIMITER $$
CREATE TRIGGER before_list_insert
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

-- Ajout d'une list : nombre d'episodes vus, soit entre 0 et le nb d'episodes de la saison
DROP TRIGGER IF EXISTS before_utilisateur_saison_insert;
DELIMITER $$
CREATE TRIGGER before_utilisateur_saison_insert
    BEFORE INSERT ON utilisateur_saison
    FOR EACH ROW
BEGIN
    DECLARE saisonNbEpisodes INT;
    SELECT nbEpisodes INTO saisonNbEpisodes FROM Saison WHERE NEW.idMedia = Saison.idSerie AND NEW.numSaison = Saison.num;
    IF NEW.nbEpisodesVus > saisonNbEpisodes THEN
        SET @s = '[table:utilisateur_saison] - [nbEpisodesVus] column is not valid';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @s;
    END IF;
END;
$$
DELIMITER ;