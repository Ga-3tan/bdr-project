<?php
/**
 * ETML
 * Author  : Marco Maziero (mazieroma)
 * Date    : 24.03.15
 * Summary : Contains the database connection class used to work with the project database.
 */

/**
 * Class dbConnect
 * Creates a connection to the database
 */
class dbConnect {
    // Class constants initialization
    const DB_HOST     = 'localhost';  // Contains the host name
    const DB_NAME     = 'bdr_projet'; // Contains the database name
    const DB_USER     = 'root';       // User name
    const DB_PASSWORD = '';           // Database connection password

    // connection declaration
    private $dbCurrentConnexion; // This will contain the current connection

    /**
     * Class constructor
     * This initializes a connection the the specified database.
     */
    public function __construct() {
        // Tries to initialize the connection to the database
        try {
            // connection options
            $options = array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

            // Initializes the connection to the database
            $this->dbCurrentConnexion = new PDO("mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . "", self::DB_USER, self::DB_PASSWORD, $options);
        } catch (PDOException $e) {
            // Displays the connection error message
            echo 'An error occurred when trying to connect to the database : ' . $e->getMessage();
        }
    }

    /**
     * Executes a specified query to the database and returns the values obtained
     * @param $strQuery     => String that contains all the query to execute
     * @param $returnArray  => Boolean indicating if an output array is expected
     * @return array associative array that contains all the values obtained with the query
     */
    private function executeSqlRequest($strQuery, $returnArray) {
        try {
            $sqlRequest = $this->dbCurrentConnexion->prepare($strQuery);
            $sqlRequest->execute();
        } catch (PDOException $e) {
            echo 'An error occurred when trying to send a query to the database : ' . $e->getMessage();
        }

        // Returns the associative array
        if ($returnArray) return $sqlRequest->fetchALL(PDO::FETCH_ASSOC);
        else return null;
    }

    public function getUserId($username) {
        return $data = $this->executeSqlRequest("SELECT idPersonne FROM Utilisateur WHERE pseudo = '" . $username . "';", true)[0]['idPersonne'];
    }

    public function getUserData($username) {
        return $data = $this->executeSqlRequest("SELECT * FROM vUtilisateur WHERE pseudo = '" . $username . "';", true);
    }

    public function getPerson($personId) {
        return $this->executeSqlRequest("SELECT * FROM Personne WHERE id = " . $personId . ";", true);
    }

    public function createUser($firstname, $lastname, $birthDate, $gender, $profilePic, $email, $username, $password) {
        // Hash password
        $hashedPwd = password_hash($password,  PASSWORD_DEFAULT);

        // Adds the user via procedure
        $this->executeSqlRequest("CALL ajouter_utilisateur('" .
                                           addslashes($lastname) . "', '" .
                                           addslashes($firstname) . "', '" .
                                           $birthDate . "', '" .
                                           $gender . "', '" .
                                           $profilePic . "', '" .
                                           addslashes($email) . "', '" .
                                           addslashes($username) . "', '" .
                                           $hashedPwd . "');", false);
    }

    public function updateUser($id, $firstname, $lastname, $birthDate, $gender, $profilePic, $email, $username) {
        // Updates the person table
        $this->executeSqlRequest("UPDATE Personne SET 
                                            nom = '" . $lastname . "', 
                                            prenom = '" . $firstname . "', 
                                            dateNaissance = '" . $birthDate . "', 
                                            sexe = '" . $gender . "', 
                                            photoProfil = '" . $profilePic . "' 
                                            WHERE id = " . $id . ";", false);

        // Updates the user table
        $this->executeSqlRequest("UPDATE Utilisateur SET 
                                           email = '" . $email . "', 
                                           pseudo = '" . $username . "' 
                                           WHERE idPersonne = " . $id . ";", false);
    }

    public function createDubber($firstname, $lastname, $birthDate, $gender, $profilePic) {
        $this->executeSqlRequest("CALL ajouter_doubleur('" .
            addslashes($firstname) . "', '" .
            addslashes($lastname) . "', '" .
            addslashes($birthDate) . "', '" .
            addslashes($gender) . "', '" .
            addslashes($profilePic) . "');", false);
    }

    public function verifyUser($username, $password) {
        // Gets all the users and their passwords
        $data = $this->executeSqlRequest("SELECT password FROM Utilisateur WHERE pseudo = '" . $username . "';", true);

        // Checks all the users and compares the values
        if (!empty($data) && password_verify($password, $data[0]['password']))
            return true;

        return false;
    }

    public function isUsernameTaken($username) {

        // Gets all the users and their passwords
        $data = $this->executeSqlRequest("SELECT pseudo FROM Utilisateur WHERE pseudo = '" . $username . "';", true);

        // Checks all the users and compares the values
        if (!empty($data)) return true;

        return false;
    }

    public function getMediaData($mediaId) {
        return $this->executeSqlRequest("SELECT * FROM vFilm WHERE id = " . $mediaId . "
                                                  UNION
                                                  SELECT * FROM vSerie WHERE id = " . $mediaId . ";", true);
    }

    public function getUserNote($username, $mediaId) {
        $usrId = $this->getUserId($username);
        return $this->executeSqlRequest("SELECT note, dateNote FROM utilisateur_media_note WHERE idPersonne = " . $usrId . " AND idMedia = " . $mediaId . ";", true);
    }

    public function getAvgNote($mediaId) {
        return $this->executeSqlRequest("SELECT AVG(note) AS 'moyenne' FROM utilisateur_media_note WHERE idMedia = " . $mediaId . ";", true);
    }

    public function getMediaDubbers($mediaId) {
        return $this->executeSqlRequest("SELECT id, nom, prenom, dateNaissance, sexe, photoProfil
                                                  FROM vDoubleur
                                                      INNER JOIN Doubleur_Media
                                                        ON vDoubleur.id = Doubleur_Media.idPersonne
                                                  WHERE Doubleur_Media.idMedia = " . $mediaId . ";", true);
    }

    public function getComments($mediaId) {
        return $this->executeSqlRequest("SELECT pseudo, commentaire, dateAjout
                                                  FROM vUtilisateur 
                                                      INNER JOIN Utilisateur_Media_Commentaire
                                                          ON id = Utilisateur_Media_Commentaire.idPersonne
                                                  WHERE idMedia = " . $mediaId . ";", true);
    }

     public function addComment($username, $mediaId, $comment) {
         $usrId = $this->getUserId($username);
         $this->executeSqlRequest("INSERT INTO Utilisateur_Media_Commentaire VALUES (" . $usrId . ", " . $mediaId . ", NOW(), '" . addslashes($comment) . "');", false);
    }

    public function getListMedia($userId, $liste) {
        return $this->executeSqlRequest('SELECT *
                                                    FROM vUtilisateur_Lists_Film
                                                    WHERE id = ' . $userId . ' AND liste = \'' . $liste . '\' 
                                                    UNION ALL
                                                    SELECT *
                                                    FROM vUtilisateur_Lists_Serie
                                                    WHERE id = ' . $userId . ' AND liste = \'' . $liste . '\';', true);
    }


    public function addNote($username, $mediaId, $note) {
        $usrId = $this->getUserId($username);
        $this->executeSqlRequest("REPLACE INTO Utilisateur_Media_Note VALUES (" . $usrId . ", " . $mediaId . ", " . $note . ", NOW());", false);
    }

    public function addMediaToList($username, $mediaId, $seasonId, $listId, $nbWatchedEp) {
        $usrId = $this->getUserId($username);
        $list = "";
        $isMovie = false;
        $mediaData = $this->getMediaData($mediaId);
        if ($mediaData[0]['nbSaisons'] == 0) return;
        if ($mediaData[0]['type'] == "Movie") $isMovie = true;

        switch ($listId) {
            case 0:
                $list = "Plan to watch";
                break;
            case 1:
                $list = "Watching";
                break;
            case 2:
                $list = "Finished";
                break;
            case 3:
                $list = "Dropped";
                break;
        }

        if ($isMovie) {
            $this->executeSqlRequest("REPLACE INTO Utilisateur_Film VALUES (" . $usrId . ", " . $mediaId . ", '" . $list . "', NOW());", false);
        } else {
            // Checks if finished, all episodes are watched
            if ($listId == 2) $nbWatchedEp = $this->getMediaSeason($mediaId, $seasonId)[0]['nbEpisodes'];

            $this->executeSqlRequest("REPLACE INTO Utilisateur_Saison VALUES (" . $usrId . ", " . $seasonId . ", " . $mediaId . ", '" . $list . "', NOW(), " . $nbWatchedEp . ");", false);
        }
    }

    public function getMediaSeason($mediaId, $seasonId) {
        return $this->executeSqlRequest("SELECT * FROM Saison WHERE idSerie = " . $mediaId . " AND num = " . $seasonId . ";", true);
    }

    public function getAllCategories() {
        return $this->executeSqlRequest("SELECT * FROM Categorie ORDER BY tag;", true);
    }

    public function getAllStudios() {
        return $this->executeSqlRequest("SELECT * FROM StudioAnimation ORDER BY nom;", true);
    }

    public function createMovie($title, $description, $duration, $picture, $studioId, $releaseDate, $categories) {
        $newId = $this->executeSqlRequest("CALL ajouter_film('" .
                                                    addslashes($title) . "', '" .
                                                    addslashes($description). "', " .
                                                    $duration . ", '" .
                                                    $picture . "', " .
                                                    $studioId . ", '" .
                                                    $releaseDate . "', @newId);", true);

        $this->addCategoryToMedia($newId[0]['newId'], $categories);
    }

    public function createSerie($title, $description, $duration, $picture, $studioId, $categories) {
        $newId = $this->executeSqlRequest("CALL ajouter_serie('" .
                                                    addslashes($title) . "', '" .
                                                    addslashes($description) . "', " .
                                                    $duration . ", '" .
                                                    $picture . "', " .
                                                    $studioId . ", @newId);", true);

        $this->addCategoryToMedia($newId[0]['newId'], $categories);
    }

    public function addCategoryToMedia($mediaId, $categories) {
        foreach ($categories as $c)
            $this->executeSqlRequest("INSERT INTO Media_Categorie VALUES ('" . $c . "', " . $mediaId . ");", false);
    }

    public function getMediaCategories($mediaId) {
        return $this->executeSqlRequest("SELECT tagCategorie FROM Media_Categorie WHERE idMedia =" . $mediaId . ";", true);
    }

    public function createSeason($mediaId, $seasonNum, $nbEpisodes, $releaseDate) {
        $this->executeSqlRequest("INSERT INTO Saison VALUES (" . $seasonNum . ", " . $mediaId . ", " . $nbEpisodes . ", '" . $releaseDate . "');", false);
    }

    public function createCategory($tag) {
        $this->executeSqlRequest("INSERT INTO Categorie VALUES ('" . addslashes($tag) . "');", false);
    }

    public function setModerator($username) {
        $usrId = $this->getUserId($username);
        $this->executeSqlRequest("INSERT INTO Moderateur VALUES (" . $usrId . ");", false);
    }

    public function addDubberToMedia($dubberId, $mediaId) {
        $this->executeSqlRequest("INSERT INTO Doubleur_Media VALUES (" . $dubberId . ", " . $mediaId . ");", false);
    }

    public function createStudio($name, $description, $logo) {
        $this->executeSqlRequest("INSERT INTO StudioAnimation VALUES (NULL, '" .
                                                                              addslashes($name) . "', '" .
                                                                              addslashes($description) . "', '" .
                                                                              $logo . "');", false);
    }

    /**
     * Class destructor
     * Destructs the class instantiation when it's not used anymore and disconnects the current database connection
     */
    public function __destruct() {
        // Disconnects the current database connection
        unset($this->currentDbconnection);
    }
}
?>