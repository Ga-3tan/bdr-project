<?php
/*
 -----------------------------------------------------------------------------------
 Projet BDR
 File        : profile_update.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 15.01.2021
 Goal        : Updates the profile
 Comment(s) : -
 -----------------------------------------------------------------------------------
 */
include "../../../lib/model/dbConnect.php";
include "utils.php";
session_start();

$db = new dbConnect();

if (isset($_POST['update_send'])) {
    $picPath = "../../img/profiles/";
    $oldData = $db->getUserData($_SESSION['USER_USERNAME'])[0];

    // Checks if username is already taken
    $newId = $db->getUserId($_POST['update_username']);
    if (!empty($newId) && $oldData['pseudo'] != $_POST['update_username']) {
        $_SESSION["UPDATE_FAIL"] = "The username " . $_POST['update_username'] . " is already taken !";
    } else {
        // Adds the new profile pic (deletes the old one)
        if (!empty($_FILES['update_picture']['name'])) {
            if ($oldData['photoProfil'] != "blank.jpg")
                unlink($picPath . $oldData['photoProfil']);
            $picName = storePicture($_FILES['update_picture'], $picPath);
        } else
            $picName = $oldData['photoProfil'];

        // Updates the user
        $db->updateUser($oldData['idPersonne'],
                        $_POST['update_firstname'],
                        $_POST['update_lastname'],
                        $_POST['update_birthdate'],
                        $_POST['update_gender'],
                        $picName,
                        $_POST['update_email'],
                        $_POST['update_username']);

        // Updates the session variables
        $_SESSION['USER_USERNAME'] = $_POST['update_username'];

        $_SESSION["UPDATE_SUCCESS"] = "The changes were successfully saved !";
    }
}

// Redirects
header('Location: ../frontend/user.php');
die();