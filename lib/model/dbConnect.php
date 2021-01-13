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