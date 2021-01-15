-- Valide la note d'un media
DROP TRIGGER IF EXISTS before_utilisateur_media_note_insert;
DELIMITER $$
CREATE TRIGGER before_utilisateur_media_note_insert
    BEFORE INSERT
    ON utilisateur_media_note
    FOR EACH ROW
BEGIN
    IF NEW.note NOT BETWEEN 1 AND 10 THEN
        SET @s = '[table:utilisateur_media_note] - [note] column is not valid';
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
    IF EXISTS (SELECT * FROM Serie WHERE Serie.idMedia = NEW.idMedia) THEN
        SET @s = '[table:serie] - New film overlapping with existing serie';
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
    IF EXISTS (SELECT * FROM Film WHERE Film.idMedia = NEW.idMedia) THEN
        SET @s = '[table:serie] - New serie overlapping with existing film';
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = @s;
    END IF;
END;
$$
DELIMITER ;