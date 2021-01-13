<?php
// Initiates the session
session_start();

// Redirects the main page or the lists page if connected
if (!isset($_SESSION['USER_LOGGED'])) {
    header('Refresh: 0;URL=web/php/frontend/welcome.php');
    die();
} else {
    header('Refresh: 0;URL=web/php/frontend/lists.php');
    die();
}
?>