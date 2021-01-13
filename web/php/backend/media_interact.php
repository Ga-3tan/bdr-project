<?php
/*
 -----------------------------------------------------------------------------------
 Projet BDR
 File        : media_interact.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 13.01.2021
 Goal        : Manages the interactions with the anime page

 Comment(s) : -
 -----------------------------------------------------------------------------------
 */
include "../../../lib/model/dbConnect.php";
session_start();

$db = new dbConnect();

if (isset($_POST['comment_send'])) {
    $db->addComment($_SESSION['USER_USERNAME'], $_GET['id'], $_POST['comment_data']);
    header('Location: ../frontend/anime.php?id=' . $_GET['id']);
    die();
} else if (isset($_POST['note_send'])) {
    $db->addNote($_SESSION['USER_USERNAME'], $_GET['id'], $_POST['note_data']);
    header('Location: ../frontend/anime.php?id=' . $_GET['id']);
    die();
} else if (isset($_POST['list_send'])) {
    $db->addMediaToList($_SESSION['USER_USERNAME'], $_GET['id'], $_POST['list_data']);
    header('Location: ../frontend/anime.php?id=' . $_GET['id']);
    die();
}