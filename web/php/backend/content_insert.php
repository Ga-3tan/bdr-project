<?php
/*
 -----------------------------------------------------------------------------------
 Projet BDR
 File        : content_insert.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 13.01.2021
 Goal        : Manages the insertion of content in the database

 Comment(s) : -
 -----------------------------------------------------------------------------------
 */
include "../../../lib/model/dbConnect.php";
include "utils.php";
session_start();

$db = new dbConnect();

if (isset($_POST['media_send'])) { // NEW MEDIA
    // Sets up the image
    $picName = storePicture($_FILES['media_picture'], "../../img/covers/");

    if ($_POST['media_type'] == "movie")
        $db->createMovie($_POST['media_title'],
            $_POST['media_description'],
            $_POST['media_duration'],
            $picName,
            $_POST['media_studio'],
            $_POST['media_release'],
            $_POST['media_category']);
    else
        $db->createSerie($_POST['media_title'],
            $_POST['media_description'],
            $_POST['media_duration'],
            $picName,
            $_POST['media_studio'],
            $_POST['media_category']);

    $_SESSION["INSERT_SUCCESS"] = "The " . $_POST['media_type'] . " " . $_POST['media_title'] . " was successfully added in the database !";

} else if (isset($_POST['season_send'])) { // NEW SEASON
    // Checks media exists and is a serie
    $mediaData = $db->getMediaData($_POST['season_media_id']);
    if (!empty($mediaData) && $mediaData[0]['type'] == 'Serie') {
        $db->createSeason($_POST['season_media_id'], $_POST['season_num'], $_POST['season_nbep'], $_POST['season_release']);
        $_SESSION["INSERT_SUCCESS"] = "The new season has been added in the database !";
    }
    else
        $_SESSION["INSERT_FAIL"] = "The media doesn't exist or is not a serie !";

} else if (isset($_POST['category_send'])) { // NEW CATEGORY
    $db->createCategory($_POST['category_name']);
    $_SESSION["INSERT_SUCCESS"] = "The category " . $_POST['category_name'] . " has been added in the database !";

} else if (isset($_POST['moderator_send'])) { // SET MODERATOR
    $usrId = $db->getUserId($_POST['moderator_username']);
    if (!empty($usrId)) {
        $db->setModerator($_POST['moderator_username']);
        $_SESSION["INSERT_SUCCESS"] = "The user " . $_POST['moderator_username'] . " is now moderator !";
    }
    else
        $_SESSION["INSERT_FAIL"] = "The username " . $_POST['moderator_username'] . " does not exist in the database !";

} else if (isset($_POST['dubber_send'])) { // NEW DUBBER
    // Sets up the profile pic
    $picName = storePicture($_FILES['dubber_photo'], "../../img/profiles/");

    // Creates the new person
    $db->createDubber($_POST['dubber_firstname'],
                      $_POST['dubber_lastname'],
                      $_POST['dubber_birthdate'],
                      $_POST['dubber_gender'],
                      $picName);

    $_SESSION["INSERT_SUCCESS"] = "The dubber " . $_POST['dubber_firstname'] . " " . $_POST['dubber_lastname'] .  " was created !";

} else if (isset($_POST['role_send'])) { // NEW DUBBER ROLE
    if (empty($db->getPerson($_POST['role_dubber']))) {
        $_SESSION["INSERT_FAIL"] = "The given dubber doesn't exist in the database.";
    }
    else if (empty($db->getMediaData($_POST['role_media']))) {
        $_SESSION["INSERT_FAIL"] = "The given media doesn't exist in the database.";
    }
    else {
        echo "CCC";
        $db->addDubberToMedia($_POST['role_dubber'], $_POST['role_media']);
        $_SESSION["INSERT_SUCCESS"] = "The dubber was given a role in the media !";
    }
} else if (isset($_POST['studio_send'])) { // NEW ANIMATION STUDIO
    $picName = storePicture($_FILES['studio_picture'], "../../img/studios/");

    // Creates the studio
    $db->createStudio($_POST['studio_name'], $_POST['studio_description'], $picName);
    $_SESSION["INSERT_SUCCESS"] = "The new animation studio " . $_POST['studio_name'] . " was created !";
}

// Redirects
header('Location: ../frontend/admin_panel.php');
die();