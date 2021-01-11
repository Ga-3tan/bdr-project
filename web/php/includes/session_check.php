<?php
/*
 -----------------------------------------------------------------------------------
 Projet BDR
 File        : session_check.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 11.01.2021
 Goal        : Stats the session and checks if the user is logged in

 Comment(s) : -
 -----------------------------------------------------------------------------------
 */

// Initiates the session
session_start();

// Checks if the user is logged in
if (!isset($_SESSION['USER_LOGGED'])) {
    header("Location: ../../../lists.php");
    die();
}
?>