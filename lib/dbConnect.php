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
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

            // Initializes the connection to the database
            $this->dbCurrentConnexion = new PDO("mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . "", self::DB_USER, self::DB_PASSWORD, $options);
        } catch (PDOException $e) {
            // Displays the connection error message
            echo 'Erreur lors de la connection à la base de données : ' . $e->getMessage();
        }
    }

    /**
     * Verifies if a username is already taken
     * @return bool
     */
    public function isUsernameTaken($username) {
        return false;
    }

    /**
     * Executes a specified query to the database and returns the values obtained
     * @param $strQuery => String that contains all the query to execute
     * @return array associative array that contains all the values obtained with the query
     */
    private function executeSqlRequest($strQuery) {
        // Prepares the request
        $sqlRequest = $this->dbCurrentConnexion->prepare($strQuery);

        // Executes the request
        $sqlRequest->execute();

        // Returns the associative array
        return $sqlRequest->fetchALL(PDO::FETCH_ASSOC);
    }

    /**
     * Executes an query to the database without returning a value
     * @param $strQuery => String that contains all the query to execute
     */
    private function executeSqlUpdate($strQuery) {
        // Prepares the request
        $sqlRequest = $this->dbCurrentConnexion->prepare($strQuery);

        // Executes the request
        $sqlRequest->execute();
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