<?php
/*
 -----------------------------------------------------------------------------------
 Projet BDR
 File        : check_registration.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 11.01.2021
 Goal        : Verifies the fields and adds the new user to the database

 Comment(s) : -
 -----------------------------------------------------------------------------------
 */

include "../../../lib/model/dbConnect.php";
session_start();

// DB Connexion
$db = new dbConnect();

// Validates the registration
if (isset($_POST['reg_send']) && validateRegistration($db)) {
    // Adds the user to the database and connects him
    $db->createUser($_POST['reg_firstname'], $_POST['reg_lastname'], $_POST['reg_email'], $_POST['reg_username'], $_POST['reg_password']);
    $_SESSION['USER_LOGGED'] = true;
    header('Location: ../frontend/lists.php');
} else {
    // Error with the fields
    $_SESSION['TAKEN_USERNAME'] = $_POST;
    header('Location: ../frontend/welcome.php');
    die();
}

/**
 * Validates all the registration form via php
 * @param $dbConnexion dbConnect Current Connection
 * @return bool boolean true if the form is valid, false if it's not
 */
function validateRegistration($dbConnexion) {

    // Variables declaration
    $isFormValid = true;

    // Checks the first name field
    if (!(preg_match('#^[a-z]+$#i', $_POST['reg_firstname']))) {
        $isFormValid = false;
    }

    // Checks the last name field
    if (!(preg_match('#^[a-z]+$#i', $_POST['reg_lastname']))) {
        $isFormValid = false;
    }

    // Checks if the username already exists
    if ($dbConnexion->isUsernameTaken($_POST['reg_username'])) {
        $isFormValid = false;
    }

    // Checks the username field
    if (!(preg_match('#^[a-z0-9_\-.]+$#i', $_POST['reg_username']))) {
        $isFormValid = false;
    }

    // Checks the mail field
    if (!(preg_match('#^[a-z0-9.]{2,}@[a-z0-9]{2,}\.([a-z]{2,4}){1}$#i', $_POST['reg_email']))) {
        $isFormValid = false;
    }

    // Checks the password
    if (!(preg_match('#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,}$#i', $_POST['reg_password']))) {
        $isFormValid = false;
    }

    // Returns the validation state
    return $isFormValid;
}
?>