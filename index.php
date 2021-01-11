<?php
// Initiates the session
session_start();

// Includes the database connection class
include './phpLib/dbConnect.php';

// Redirects the user if he's not logged in
if (!(isset($_SESSION['USER_LOGGED'])))
{
    // Redirects to the index page
    header('Refresh: 0;URL=web/php/frontend/welcome.html');
} else {
    // Redirects to the the main page
    header('Refresh: 0;URL=web/php/frontend/search.html');
}
?>