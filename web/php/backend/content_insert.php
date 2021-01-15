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
session_start();

$db = new dbConnect();

// Media creation
if (isset($_POST['media_send'])) {
    // Sets up the image
    $strCurrentExtension = pathinfo($_FILES['media_picture']['name'])['extension'];
    date_default_timezone_set('Europe/Zurich');
    $strFileName = date("Ymdhis") . rand() . "." . $strCurrentExtension;
    $strOldLocation = $_FILES['media_picture']['tmp_name'];
    $strNewLocation = "../../img/covers/";
    move_uploaded_file($strOldLocation, $strNewLocation . $strFileName);

    if ($_POST['media_type'] == "movie")
        $db->createMovie($_POST['media_title'],
            $_POST['media_description'],
            $_POST['media_duration'],
            $strFileName,
            $_POST['media_studio'],
            $_POST['media_release'],
            $_POST['media_category']);
    else
        $db->createSerie($_POST['media_title'],
            $_POST['media_description'],
            $_POST['media_duration'],
            $strFileName,
            $_POST['media_studio'],
            $_POST['media_category']);

    header('Location: ../frontend/insert.php');
    die();
}