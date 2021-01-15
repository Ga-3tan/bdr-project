<?php
/*
 -----------------------------------------------------------------------------------
 Projet BDR
 File        : utils.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 15.01.2021
 Goal        : Contains useful functions
 Comment(s) : -
 -----------------------------------------------------------------------------------
 */

/** Stores the picture and returns the name
 * @param $picture The picture to store
 * @return string The name
 */
function storePicture($picture, $dst) {
    $strCurrentExtension = pathinfo($picture['name'])['extension'];
    date_default_timezone_set('Europe/Zurich');
    $strFileName = date("Ymdhis") . rand() . "." . $strCurrentExtension;
    $strOldLocation = $picture['tmp_name'];
    move_uploaded_file($strOldLocation, $dst . $strFileName);
    return $strFileName;
}