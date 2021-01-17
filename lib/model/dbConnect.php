<?php
/*
 -------------------------------------------------------------------------------------
 Projet BDR
 File        : dbConnect.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 11.01.2021
 Goal        : Contains the class used to communicate with the database

 Comment(s) : -
 ------------------------------------------------------------------------------------
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

    // Connection attribute
    private $dbCurrentConnexion; // This will contain the current connection

    /**
     * Class constructor
     * This initializes a connection to the specified database
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

    /**
     * Retrieves the user id
     * @param $username string :: The username
     * @return mixed :: The array containing the user id
     */
    public function getUserId($username) {
        return $data = $this->executeSqlRequest("SELECT idPersonne FROM Utilisateur WHERE pseudo = '" . $username . "';", true)[0]['idPersonne'];
    }

    /**
     * Retrieves all the user data
     * @param $username string :: The username
     * @return array|null :: The user data (null if the given user doesn't exist)
     */
    public function getUserData($username) {
        return $data = $this->executeSqlRequest("SELECT * FROM vUtilisateur WHERE pseudo = '" . $username . "';", true);
    }

    /**
     * Retrieves a person info
     * @param $personId int :: The id
     * @return array|null :: The person data
     */
    public function getPerson($personId) {
        return $this->executeSqlRequest("SELECT * FROM Personne WHERE id = " . $personId . ";", true);
    }

    /**
     *  Creates a new user in the database with the defined SQL procedure
     * @param $firstname string :: User first name
     * @param $lastname string :: User last name
     * @param $birthDate string :: User birth date, format 'YYYY-MM-DD'
     * @param $gender string :: User gender, must be {'homme', 'femme', 'autre'}
     * @param $profilePic string :: User profile picture link
     * @param $email string :: User email address
     * @param $username string :: Username
     * @param $password string :: The password, will be hashed before insert into the db
     */
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

    /**
     * Updates the user data
     * @param $id int :: User id
     * @param $firstname string :: User first name
     * @param $lastname string :: User last name
     * @param $birthDate string :: User birth date, format 'YYYY-MM-DD'
     * @param $gender string :: User gender, must be {'homme', 'femme', 'autre'}
     * @param $profilePic string :: User profile picture link
     * @param $email string :: User email address
     * @param $username string :: Username
     */
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

    /**
     * Creates a new media dubber
     * @param $firstname string :: Dubber first name
     * @param $lastname string :: Dubber last name
     * @param $birthDate string :: Dubber birth date, format 'YYYY-MM-DD'
     * @param $gender string :: Dubber gender, must be {'homme', 'femme', 'autre'}
     * @param $profilePic string :: Dubber profile picture link
     */
    public function createDubber($firstname, $lastname, $birthDate, $gender, $profilePic) {
        $this->executeSqlRequest("CALL ajouter_doubleur('" .
            addslashes($firstname) . "', '" .
            addslashes($lastname) . "', '" .
            addslashes($birthDate) . "', '" .
            addslashes($gender) . "', '" .
            addslashes($profilePic) . "');", false);
    }

    /**
     * Returns a boolean telling weather a username exists in the db or not and if the passwod matches
     * @param $username string :: Username
     * @param $password string :: Userpassword (will be hashed for comparaison with value in db)
     * @return bool :: The result, true if the user exists and the password matches
     */
    public function verifyUser($username, $password) {
        // Gets all the users and their passwords
        $data = $this->executeSqlRequest("SELECT password FROM Utilisateur WHERE pseudo = '" . $username . "';", true);

        // Checks all the users and compares the values
        if (!empty($data) && password_verify($password, $data[0]['password']))
            return true;

        return false;
    }

    /**
     * Tells if a username is already taken
     * @param $username string :: Username
     * @return bool :: True if the username is taken
     */
    public function isUsernameTaken($username) {

        // Gets all the users and their passwords
        $data = $this->executeSqlRequest("SELECT pseudo FROM Utilisateur WHERE pseudo = '" . $username . "';", true);

        // Checks all the users and compares the values
        if (!empty($data)) return true;

        return false;
    }

    /**
     * Retrieves the data from a given media
     * @param $mediaId int :: The media id
     * @return array|null :: The data (null if the media does not exist)
     */
    public function getMediaData($mediaId) {
        return $this->executeSqlRequest("SELECT * FROM vFilm WHERE id = " . $mediaId . "
                                                  UNION ALL
                                                  SELECT * FROM vSerie WHERE id = " . $mediaId . ";", true);
    }

    /**
     * Gets a list of medias corresponding to certain criteria
     * @param $name string :: The media title
     * @param $category string :: The media category
     * @param $studio string :: The media animation studio
     * @param $order string :: The order of results, must be {'titre', 'score'}
     * @param $type string :: The wanted type of media, must be {'movie', 'serie', 'all'}
     * @return array|null :: Array containing all the macthing medias
     */
    public function searchMedia($name, $category, $studio, $order, $type) {
        if ($order != 'titre' && $order != 'score')
            $order = 'titre';
        if ($order == 'score') $order = 'score DESC';

        if ($type == 'movie') {
            return $this->executeSqlRequest("SELECT * FROM vFilm 
                                                            INNER JOIN Media_Categorie 
                                                                ON id = idMedia
                                                                AND tagCategorie LIKE '%" . $category . "%' 
                                                      WHERE titre LIKE '%" . $name . "%' AND nomStudio LIKE '%" . $studio . "%'
                                                      GROUP BY titre
                                                      ORDER BY " . $order . " LIMIT 50;", true);
        } else if ($type == 'serie') {
            return $this->executeSqlRequest("SELECT * FROM vSerie 
                                                            INNER JOIN Media_Categorie 
                                                                ON id = idMedia
                                                                AND tagCategorie LIKE '%" . $category . "%' 
                                                      WHERE titre LIKE '%" . $name . "%' AND nomStudio LIKE '%" . $studio . "%'
                                                      GROUP BY titre
                                                      ORDER BY " . $order . " LIMIT 50;", true);
        } else {
            return $this->executeSqlRequest("SELECT * FROM (
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
                                                  ORDER BY " . $order . " LIMIT 50;", true);
        }
    }

    /**
     * Gets the note a user gave to a media
     * @param $username string :: The username
     * @param $mediaId int :: The media id
     * @return array|null :: The given note (null if the media / user doesn't exist)
     */
    public function getUserNote($username, $mediaId) {
        $usrId = $this->getUserId($username);
        return $this->executeSqlRequest("SELECT note, dateNote FROM utilisateur_media_note WHERE idPersonne = " . $usrId . " AND idMedia = " . $mediaId . ";", true);
    }

    /**
     * Gets the average score of one media
     * @param $mediaId int :: The media id
     * @return array|null :: The score
     */
    public function getAvgNote($mediaId) {
        return $this->executeSqlRequest("SELECT AVG(note) AS 'moyenne' FROM utilisateur_media_note WHERE idMedia = " . $mediaId . ";", true);
    }

    /**
     * Gets all the dubbers of one given media
     * @param $mediaId int :: The media id
     * @return array|null :: Array containing the dubbers data
     */
    public function getMediaDubbers($mediaId) {
        return $this->executeSqlRequest("SELECT id, nom, prenom, dateNaissance, sexe, photoProfil
                                                  FROM vDoubleur
                                                      INNER JOIN Doubleur_Media
                                                        ON vDoubleur.id = Doubleur_Media.idPersonne
                                                  WHERE Doubleur_Media.idMedia = " . $mediaId . ";", true);
    }

    /**
     * Gets all the comments of one given media
     * @param $mediaId int :: The media id
     * @return array|null :: Array containing the comments data
     */
    public function getComments($mediaId) {
        return $this->executeSqlRequest("SELECT pseudo, commentaire, dateAjout
                                                  FROM vUtilisateur 
                                                      INNER JOIN Utilisateur_Media_Commentaire
                                                          ON id = Utilisateur_Media_Commentaire.idPersonne
                                                  WHERE idMedia = " . $mediaId . ";", true);
    }

    /**
     * Creates a new comment and attaches it to a media
     * @param $username string :: The username
     * @param $mediaId int :: The media id
     * @param $comment string :: The comment text
     */
     public function addComment($username, $mediaId, $comment) {
         $usrId = $this->getUserId($username);
         $this->executeSqlRequest("INSERT INTO Utilisateur_Media_Commentaire VALUES (" . $usrId . ", " . $mediaId . ", NOW(), '" . addslashes($comment) . "');", false);
    }

    /**
     * Retrieves a list of a given user
     * @param $userId int :: The user id
     * @param $liste string :: The list name, must be {'Plan to watch', 'Watching', 'Finished', 'Dropped'}
     * @return array|null :: All the medias in the user list
     */
    public function getListMedia($userId, $liste) {
        return $this->executeSqlRequest('SELECT *
                                                    FROM vUtilisateur_Lists_Film
                                                    WHERE id = ' . $userId . ' AND liste = \'' . $liste . '\' 
                                                    UNION ALL
                                                    SELECT *
                                                    FROM vUtilisateur_Lists_Serie
                                                    WHERE id = ' . $userId . ' AND liste = \'' . $liste . '\';', true);
    }

    /**
     * Adds a note to a media
     * @param $username string :: The username
     * @param $mediaId int :: The media id
     * @param $note int :: The given note
     */
    public function addNote($username, $mediaId, $note) {
        $usrId = $this->getUserId($username);
        $this->executeSqlRequest("REPLACE INTO Utilisateur_Media_Note VALUES (" . $usrId . ", " . $mediaId . ", " . $note . ", NOW());", false);
    }

    /**
     * Adds a media to a user list
     * @param $username string :: The username
     * @param $mediaId int :: The media id
     * @param $seasonId int :: The season id (if its a serie)
     * @param $listId int :: The list id, must be {0, 1, 2, 3}
     * @param $nbWatchedEp int :: Number of watched episodes
     */
    public function addMediaToList($username, $mediaId, $seasonId, $listId, $nbWatchedEp) {
        $usrId = $this->getUserId($username);
        $list = "";
        $isMovie = false;
        $mediaData = $this->getMediaData($mediaId);
        if ($mediaData[0]['nbSaisons'] == 0) return;
        if ($mediaData[0]['type'] == "Movie") $isMovie = true;

        // Gets the list name depending on id
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

    /**
     * Retrieves a media season
     * @param $mediaId int :: The media id
     * @param $seasonId int :: The season id
     * @return array|null :: The season data
     */
    public function getMediaSeason($mediaId, $seasonId) {
        return $this->executeSqlRequest("SELECT * FROM Saison WHERE idSerie = " . $mediaId . " AND num = " . $seasonId . ";", true);
    }

    /**
     * Gets all the registered media categories
     * @return array|null :: Array with the categories names
     */
    public function getAllCategories() {
        return $this->executeSqlRequest("SELECT * FROM Categorie ORDER BY tag;", true);
    }

    /**
     * Gets all the registered animation studios
     * @return array|null :: Array with the studios data
     */
    public function getAllStudios() {
        return $this->executeSqlRequest("SELECT * FROM StudioAnimation ORDER BY nom;", true);
    }

    /**
     * Creates a new movie in the database with the defined SQL procedure
     * @param $title string :: The new name
     * @param $description string :: The new description
     * @param $duration int :: Duration
     * @param $picture string :: Cover picture link
     * @param $studioId int :: Animation studio id
     * @param $releaseDate string :: The release date, format 'YYYY-MM-DD'
     * @param $categories string[] :: All the categories
     */
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

    /**
     * Creates a new serie in the database with the defined SQL procedure
     * @param $title string :: The new name
     * @param $description string :: The new description
     * @param $duration int :: Duration
     * @param $picture string :: Cover picture link
     * @param $studioId int :: Animation studio id
     * @param $categories string[] :: All the categories
     */
    public function createSerie($title, $description, $duration, $picture, $studioId, $categories) {
        $newId = $this->executeSqlRequest("CALL ajouter_serie('" .
                                                    addslashes($title) . "', '" .
                                                    addslashes($description) . "', " .
                                                    $duration . ", '" .
                                                    $picture . "', " .
                                                    $studioId . ", @newId);", true);

        $this->addCategoryToMedia($newId[0]['newId'], $categories);
    }

    /**
     * Adds a category to a media
     * @param $mediaId int :: Media id
     * @param $categories string :: The category to bind
     */
    public function addCategoryToMedia($mediaId, $categories) {
        foreach ($categories as $c)
            $this->executeSqlRequest("INSERT INTO Media_Categorie VALUES ('" . $c . "', " . $mediaId . ");", false);
    }

    /**
     * Retrieves all the categories bound to a given media
     * @param $mediaId int :: Media id
     * @return array|null :: Array containing all the categories names
     */
    public function getMediaCategories($mediaId) {
        return $this->executeSqlRequest("SELECT tagCategorie FROM Media_Categorie WHERE idMedia =" . $mediaId . ";", true);
    }

    /**
     * Creates a new season
     * @param $mediaId int :: Media id
     * @param $seasonNum int :: Season number
     * @param $nbEpisodes int :: Number of episodes in the season
     * @param $releaseDate string :: Season release date, format 'YYYY-MM-DD'
     */
    public function createSeason($mediaId, $seasonNum, $nbEpisodes, $releaseDate) {
        $this->executeSqlRequest("INSERT INTO Saison VALUES (" . $seasonNum . ", " . $mediaId . ", " . $nbEpisodes . ", '" . $releaseDate . "');", false);
    }

    /**
     * Creates a new category
     * @param $tag string :: Category name
     */
    public function createCategory($tag) {
        $this->executeSqlRequest("INSERT INTO Categorie VALUES ('" . addslashes($tag) . "');", false);
    }

    /**
     * Sets a existing user moderator
     * @param $username string :: The user name
     */
    public function setModerator($username) {
        $usrId = $this->getUserId($username);
        $this->executeSqlRequest("INSERT INTO Moderateur VALUES (" . $usrId . ");", false);
    }

    /**
     * Binds a dubber to a media
     * @param $dubberId int :: Dubber id
     * @param $mediaId int :: Media id
     */
    public function addDubberToMedia($dubberId, $mediaId) {
        $this->executeSqlRequest("INSERT INTO Doubleur_Media VALUES (" . $dubberId . ", " . $mediaId . ");", false);
    }

    /**
     * Creates a new animation studio
     * @param $name string :: Studio name
     * @param $description string :: Studio description
     * @param $logo string :: Studio logo image link
     */
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