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
            // Prepares the request
            $sqlRequest = $this->dbCurrentConnexion->prepare($strQuery);
            // Executes the request
            $sqlRequest->execute();
        } catch (PDOException $e) {
            echo 'An error occurred when trying to send a query to the database : ' . $e->getMessage();
        }

        // Returns the associative array
        if ($returnArray) return $sqlRequest->fetchALL(PDO::FETCH_ASSOC);
        else return null;
    }

    private function getUserId($username) {
        return $data = $this->executeSqlRequest("SELECT idPersonne FROM Utilisateur WHERE pseudo = '" . $username . "';", true)[0]['idPersonne'];
    }

    public function createUser($firstname, $lastname, $email, $username, $password) {
        // Hash password
        $hashedPwd = password_hash($password,  PASSWORD_DEFAULT);

        // Adds the user in Person table
        $this->executeSqlRequest("INSERT INTO Personne(nom, prenom)
                                 VALUES ('" . addslashes($firstname) . "', '" . addslashes($lastname) . "');", false);

        // Adds the user in User table
        $this->executeSqlRequest("INSERT INTO Utilisateur(email, pseudo, password, idPersonne)
                                 VALUES ('" . addslashes($email) . "', '" . addslashes($username) . "', '" . $hashedPwd . "', " . $this->dbCurrentConnexion->lastInsertId() . ");", false);
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

    public function getDubbers($mediaId) {
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
        return $this->executeSqlRequest('SELECT media, liste, image, idMedia, \'film\' AS \'categorie\'
                                                    FROM vUtilisateur_Lists_Film
                                                    WHERE id = ' . $userId . ' AND liste = \'' . $liste . '\' 
                                                    UNION 
                                                    SELECT media, liste, image, idMedia, \'serie\' AS \'categorie\'
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