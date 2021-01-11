<?php
// Initiates the session
session_start();

// Checks if the user is logged in
if (!isset($_SESSION['USER_LOGGED'])) {
    // header('Refresh: 0;URL=../../../index.php');
}
?>