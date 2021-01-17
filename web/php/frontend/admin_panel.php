<?php
/*
 -------------------------------------------------------------------------------------
 Projet BDR
 File        : admin_panel.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 11.01.2021
 Goal        : Page used by moderators to insert content into the database

 Comment(s) : -
 ------------------------------------------------------------------------------------
*/

include '../includes/session_check.php';
include '../includes/header_nav.php';
include "../../../lib/model/dbConnect.php";

$db = new dbConnect();

// Gets all categories and studios
$userData = $db->getUserData($_SESSION['USER_USERNAME'])[0];
// Checks access rights
if (!$userData['moderateur']) {
    header("Location: user.php");
    die();
}

$categoryData = $db->getAllCategories();
$studioData   = $db->getAllStudios();
?>
<div style="margin: 100px;">

    <!-- DISPLAY SCRIPT -->
    <script>
        function displayOptions(id) {
            let x = document.getElementById(id);
            if (x.className.indexOf("w3-show") === -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
            }
        }
    </script>

    <!--DISPLAY MESSAGES-->
    <ul class="w3-ul w3-large">
        <?php
        if (isset($_SESSION["INSERT_FAIL"])) {
            echo '<div class="w3-panel w3-red w3-animate-opacity"><h3>' . $_SESSION["INSERT_FAIL"] . '</h3></div>';
            unset($_SESSION["INSERT_FAIL"]);
        } else if (isset($_SESSION["INSERT_SUCCESS"])) {
            echo '<div class="w3-panel w3-green w3-animate-opacity"><h3>' . $_SESSION["INSERT_SUCCESS"] . '</h3></div>';
            unset($_SESSION["INSERT_SUCCESS"]);
        }
        ?>

        <!--ADD MEDIA-->
        <div onclick="displayOptions('add_media')" class="w3-container w3-black w3-btn w3-button w3-block w3-left-align">
            <h3>Add media</h3>
        </div>

        <form id="add_media" class="w3-animate-opacity w3-container w3-hide" action="../backend/content_insert.php" method="post" enctype="multipart/form-data">
        <li>
            <label for="title">Title</label>
            <br>
            <input type="text" class="w3-input"
                   id="title" name="media_title" maxlength="45" required>
        </li>
        <li>
            <label for="description">Description</label>
            <br>
            <input type="text" class="w3-input"
                   id="description" name="media_description" maxlength="2000" required>
        </li>
        <li>
            <label for="duration">Duration</label>
            <br>
            <input type="number" class="w3-input"
                   min="0" max="500" id="duration" name="media_duration" required>
        </li>
        <li>
            <label for="mediaPicture">Picture</label>
            <br>
            <input type="file" class="w3-input"
                   id="mediaPicture" name="media_picture"
                   accept="image/png, image/jpeg" required>
        </li>
        <li>
            <label for="studio">Studio
                <br>
                <select class="w3-select w3-input" id="studio" name="media_studio">
                    <?php
                    foreach ($studioData as $s)
                        echo '<option value="' . $s['id'] . '">' . $s['nom'] . '</option>';
                    ?>
                </select>
            </label>
        </li>
        <li>
            <input class="w3-radio" type="radio" name="media_type" value="movie" checked>
            <label>Movie</label>

            <input class="w3-radio" type="radio" name="media_type" value="serie">
            <label>Serie</label>
        </li>
        <li>
            <label for="mediaPicture">Release date (Only for movie)</label>
            <br>
            <input type="date" id="start" name="media_release" class="w3-input"
                   min="1900-01-01" max="2999-12-31" value="2000-01-01" required>
        </li>
        <li>
            <label for="mediaPicture">Categories</label>
            <div class="w3-border" style="height: 300px; width: 100%; overflow: scroll">
                <?php
                foreach ($categoryData as $c) {
                    echo '<div>
                             <input class="w3-check" id="categorie-1" type="checkbox" name="media_category[]" value="' . $c['tag'] . '">
                             <label>' . $c['tag'] . '</label>
                          </div>';
                }
                ?>
            </div>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="media_send">CREATE NEW MEDIA</button>
        </li>
        </form>

        <!--ADD SEASON TO MEDIA-->
        <div onclick="displayOptions('add_season')" class="w3-container w3-black w3-btn w3-button w3-block w3-left-align">
            <h3>Add season</h3>
        </div>

        <form id="add_season" class="w3-animate-opacity w3-container w3-hide" action="../backend/content_insert.php" method="post">
        <li>

            <label for="mediaID">Media ID</label>
            <input type="number" value="" class="w3-input"
                   id="mediaID" name="season_media_id"
                   min="0" required>
            <label for="season">Season n°</label>
            <input type="number" value="1" min="1" class="w3-input"
                   id="season" name="season_num"
                   min="0" required>
            <label for="episode">Nb of episodes (1 for film)</label>
            <input type="number" value="1" min="1" class="w3-input"
                   id="episode" name="season_nbep" required>
            <label for="dateSeason">Release date</label>
            <input type="date" name="season_release" class="w3-input"
                   id="dateSeason"  min="1900-01-01" max="2999-12-31"
                   value="2000-01-01" required>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="season_send">CREATE NEW SEASON TO A SERIE</button>

        </form>

        <!--ADD CATEGORY -->
        <div onclick="displayOptions('add_category')" class="w3-container w3-black w3-btn w3-button w3-block w3-left-align">
            <h3>Add new category</h3>
        </div>

        <form id="add_category" class="w3-animate-opacity w3-container w3-hide" action="../backend/content_insert.php" method="post">
            <li>
                <label for="categoryName">Category name</label>
                <input type="text" id="categoryName"maxlength="50" class="w3-input"
                       name="category_name" required>
            </li>
            <li>
                <button class="w3-button w3-black" type="submit" value="Send" name="category_send">CREATE NEW CATEGORY</button>
            </li>
        </form>

        <!--SET MODERATOR-->
        <div onclick="displayOptions('add_moderator')" class="w3-container w3-black w3-btn w3-button w3-block w3-left-align">
            <h3>Set Moderator</h3>
        </div>

        <form id="add_moderator" class="w3-animate-opacity w3-container w3-hide" action="../backend/content_insert.php" method="post">
        <li>
            <label for="username">Username</label>
            <input type="text" id="username" class="w3-input"
                   name="moderator_username" required>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="moderator_send">CREATE NEW MODERATOR</button>
        </li>
        </form>

        <!--ADD VOICE ACTOR-->
        <div onclick="displayOptions('add_dubber')" class="w3-container w3-black w3-btn w3-button w3-block w3-left-align">
            <h3>Add Voice actor</h3>
        </div>

        <form id="add_dubber" class="w3-animate-opacity w3-container w3-hide" action="../backend/content_insert.php" method="post" enctype="multipart/form-data">
        <li>

            <label for="dubberFirstname">First name</label><br>
            <input type="text" value="" class="w3-input"
                   id="dubberFirstname" name="dubber_firstname" required>
        </li>
        <li>
            <label for="dubberLastname">Last name</label><br>
            <input type="text" value="" class="w3-input"
                   id="dubberLastname" name="dubber_lastname" required>
        </li>
        <li>
            <label for="dubberBirthdate">Birth date</label><br>
            <input type="date" value="" class="w3-input"
                   id="personFirstName" name="dubber_birthdate" required>
        </li>
        <li>
            <label for="dubberGender">Gender</label><br>
            <input class="w3-radio" type="radio" name="dubber_gender" value="homme" checked>
            <label>Male</label>

            <input class="w3-radio" type="radio" name="dubber_gender" value="femme">
            <label>Female</label>

            <input class="w3-radio" type="radio" name="dubber_gender" value="autre">
            <label>Other</label>
        </li>
        <li>
            <label for="dubberPhoto">Profile photo</label><br>
            <input type="file" value="" accept="image/png, image/jpeg" class="w3-input"
                   id="personFirstName" name="dubber_photo" required>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="dubber_send">CREATE NEW VOICE ACTOR</button>
        </li></form>

        <!--ADD ROLE TO VOICE ACTOR-->
        <div onclick="displayOptions('add_role')" class="w3-container w3-black w3-btn w3-button w3-block w3-left-align">
            <h3>Add Voice actor role</h3>
        </div>

        <form id="add_role" class="w3-animate-opacity w3-container w3-hide" action="../backend/content_insert.php" method="post">
        <li>

            <label for="person_roleID">Person ID</label>
            <input type="number" min="0" class="w3-input"
                   id="person_roleID" name="role_dubber" required>
            <label for="media_roleID">Media ID</label>
            <input type="number" min="0" class="w3-input"
                   id="media_roleID" name="role_media" required>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="role_send">CREATE NEW ROLE</button>
        </li>

        </form>

        <!--ADD ANIMATION STUDIO-->
        <div onclick="displayOptions('add_studio')" class="w3-container w3-black w3-btn w3-button w3-block w3-left-align">
            <h3>Add animation studio</h3>
        </div>

        <form id="add_studio" class="w3-animate-opacity w3-container w3-hide" action="../backend/content_insert.php" method="post" enctype="multipart/form-data">
        <li>

            <label for="studio_name">Name</label>
            <br>
            <input type="text" maxlength="100" class="w3-input"
                   id="studio_name" name="studio_name" required>
        </li>
        <li>
            <label for="studio_description">Description</label>
            <br>
            <input type="text" class="w3-input"
                   id="studio_description" name="studio_description" required>
        </li>
        <li>
            <label for="studio_picture">Picture</label>
            <br>
            <input type="file" class="w3-input"
                   id="studio_picture" name="studio_picture"
                   accept="image/png, image/jpeg" required>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="studio_send">CREATE NEW STUDIO</button>
        </li>
        </form>
    </ul>
</div>

</body>
</html>
