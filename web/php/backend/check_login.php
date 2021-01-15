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

include "../../../lib/model/dbConnect.php";
session_start();

// DB Connexion
$db = new dbConnect();

// Validates the registration
if (isset($_POST['login_send']) && validateLogin($db)) {
    // Adds the user to the database and connects him
    $_SESSION['USER_LOGGED'] = true;
    $_SESSION['USER_USERNAME'] = $_POST['login_username'];
    $v = $db->getUserId($_POST['login_username']);
    $_SESSION['USER_ID'] = $db->getUserId($_POST['login_username']);
    header('Location: ../frontend/lists.php');
} else {
    // Error with the fields
    $_SESSION['WRONG_LOGIN'] = $_POST;
    header('Location: ../frontend/welcome.php');
    die();
}

/**
 * Validates all the login form via php
 * @param $dbConnexion dbConnect Current Connection
 * @return bool boolean true if the form is valid, false if it's not
 */
function validateLogin($dbConnexion) {
    return $dbConnexion->verifyUser($_POST['login_username'], $_POST['login_password']);
}