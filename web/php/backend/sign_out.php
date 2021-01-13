<?php
/*
-----------------------------------------------------------------------------------
Projet BDR
File        : sign_out.php
Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
Date        : 13.01.2021
Goal        : Unlogs the user

Comment(s) : -
-----------------------------------------------------------------------------------
*/
session_start();

// Checks if the user is logged in
session_destroy();

// Redirects
header("Location: ../../../index.php");
die();
?>