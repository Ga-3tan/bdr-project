<?php
/*
 -----------------------------------------------------------------------------------
 Projet BDR
 File        : check_login.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 11.01.2021
 Goal        : Verifies the fields for the login page and connects the user

 Comment(s) : -
 -----------------------------------------------------------------------------------
 */

include "../../../lib/dbConnect.php";
session_start();

// DB Connexion
$db = new dbConnect();

// Validates the registration
if (isset($_POST['login_send']) && validateLogin($db)) {
    // Adds the user to the database and connects him
    // TODO : CREATE USER IN DATABASE
    $_SESSION['USER_LOGGED'] = true;
    header('Location: ../frontend/lists.php');
} else {
    // Error with the fields
    $_SESSION['WRONG_LOGIN'] = $_POST;
    header('Location: ../frontend/welcome.php');
    die();
}

/**
 * Validates all the login form via php
 * @return bool boolean true if the form is valid, false if it's not
 */
function validateLogin($dbConnexion) {

    // Variables declaration
    $isFormValid = true;

    // TODO : CHECK USERNAME AND PASSWORD IN DB

    // Returns the validation state
    return false;
}