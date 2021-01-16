<?php
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
    <!--ADD MEDIA-->
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
        <li style="background-color: black"></li><form action="../backend/content_insert.php" method="post" enctype="multipart/form-data">

        <li>
            <h3>Add media</h3>

            <label for="title">Title:</label>
            <br>
            <input type="text"
                   id="title" name="media_title" maxlength="45" required>
        </li>
        <li>
            <label for="description">Description:</label>
            <br>
            <input type="text"
                   id="description" name="media_description" maxlength="2000" required>
        </li>
        <li>
            <label for="duration">Duration:</label>
            <br>
            <input type="number"
                   min="0" max="500" id="duration" name="media_duration" required>
        </li>
        <li>
            <label for="mediaPicture">Picture:</label>
            <br>
            <input type="file"
                   id="mediaPicture" name="media_picture"
                   accept="image/png, image/jpeg" required>
        </li>
        <li>
            <label for="studio">Studio:
                <br>
                <select class="w3-select" id="studio" name="media_studio" style="width: 500px">
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
            <label for="mediaPicture">Release date (Only for movie):</label>
            <br>
            <input type="date" id="start" name="media_release"
                   min="1900-01-01" max="2999-12-31" value="2000-01-01" required>
        </li>
        <li>
            <div style="height: 300px; width: 600px; overflow: scroll">
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

        </form><li style="background-color: black"></li>

        <!--ADD SEASON TO MEDIA-->
        <form action="../backend/content_insert.php" method="post">
        <li>
            <h3>Add season</h3>

            <label for="mediaID">Media ID:</label>
            <input type="number" value=""
                   id="mediaID" name="season_media_id"
                   min="0" required>
            <label for="season">Season n°</label>
            <input type="number" value="1" min="1"
                   id="season" name="season_num"
                   min="0" required>
            <label for="episode">Nb of episode (1 for film):</label>
            <input type="number" value="1" min="1"
                   id="episode" name="season_nbep" required>
            <label for="dateSeason">Release date</label>
            <input type="date" name="season_release"
                   id="dateSeason"  min="1900-01-01" max="2999-12-31"
                   value="2000-01-01" required>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="season_send">CREATE NEW SEASON TO A SERIE</button>

        <li style="background-color: black"></li></form>

        <!--ADD CATEGORY -->
        <form action="../backend/content_insert.php" method="post">
            <li>
                <h3>Add new category</h3>

                <label for="categoryName">Category name:</label>
                <input type="text" id="categoryName"maxlength="50"
                       name="category_name" required>
            </li>
            <li>
                <button class="w3-button w3-black" type="submit" value="Send" name="category_send">CREATE NEW CATEGORY</button>
            </li>
            <li style="background-color: black"></li></form>

        <!--SET MODERATOR-->
        <form action="../backend/content_insert.php" method="post">
        <li>
            <h3>Set Moderator</h3>

            <label for="username">Username:</label>
            <input type="text" id="username"
                   name="moderator_username" required>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="moderator_send">CREATE NEW MODERATOR</button>
        </li>
        <li style="background-color: black"></li></form>

        <!--ADD VOICE ACTOR-->
        <form action="../backend/content_insert.php" method="post" enctype="multipart/form-data">
        <li>
            <h3>Add Voice actor</h3>
            <label for="dubberFirstname">First name:</label><br>
            <input type="text" value=""
                   id="dubberFirstname" name="dubber_firstname" required>
        </li>
        <li>
            <label for="dubberLastname">Last name:</label><br>
            <input type="text" value=""
                   id="dubberLastname" name="dubber_lastname" required>
        </li>
        <li>
            <label for="dubberBirthdate">Birth date:</label><br>
            <input type="date" value=""
                   id="personFirstName" name="dubber_birthdate" required>
        </li>
        <li>
            <label for="dubberGender">Gender:</label><br>
            <input class="w3-radio" type="radio" name="dubber_gender" value="homme" checked>
            <label>Male</label>

            <input class="w3-radio" type="radio" name="dubber_gender" value="femme">
            <label>Female</label>

            <input class="w3-radio" type="radio" name="dubber_gender" value="autre">
            <label>Other</label>
        </li>
        <li>
            <label for="dubberPhoto">Profile photo:</label><br>
            <input type="file" value="" accept="image/png, image/jpeg"
                   id="personFirstName" name="dubber_photo" required>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="dubber_send">CREATE NEW VOICE ACTOR</button>
        </li>

        <li style="background-color: black"></li>
        </form>
        <!--ADD ROLE TO VOICE ACTOR-->
        <form action="../backend/content_insert.php" method="post">
        <li>
            <h3>Add Voice actor role</h3>
            <label for="person_roleID">Person ID:</label>
            <input type="number" min="0"
                   id="person_roleID" name="role_dubber" required>
            <label for="media_roleID">Media ID:</label>
            <input type="number" min="0"
                   id="media_roleID" name="role_media" required>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="role_send">CREATE NEW ROLE</button>
        </li>

        <li style="background-color: black"></li>
        </form>

        <!--ADD ANIMATION STUDIO-->
        <form action="../backend/content_insert.php" method="post" enctype="multipart/form-data">
        <li>
            <h3>Add animation studio</h3>
            <label for="studio_name">name:</label>
            <br>
            <input type="text" maxlength="100"
                   id="studio_name" name="studio_name" required>
        </li>
        <li>
            <label for="studio_description">Description:</label>
            <br>
            <input type="text"
                   id="studio_description" name="studio_description" required>
        </li>
        <li>
            <label for="studio_picture">Picture:</label>
            <br>
            <input type="file"
                   id="studio_picture" name="studio_picture"
                   accept="image/png, image/jpeg" required>
        </li>
        <li>
            <button class="w3-button w3-black" type="submit" value="Send" name="studio_send">CREATE NEW STUDIO</button>
        </li>

        <li style="background-color: black"></li>
    </ul>
</div>

</body>
</html>
