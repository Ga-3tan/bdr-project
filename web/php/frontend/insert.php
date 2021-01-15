<?php
include '../includes/session_check.php';
include '../includes/header_nav.php';
include "../../../lib/model/dbConnect.php";

$db = new dbConnect();

// Gets all categories and studios
$categoryData = $db->getAllCategories();
$studioData   = $db->getAllStudios();
?>
<div style="margin: 100px;">

    <!--ADD MEDIA-->
    <ul class="w3-ul w3-large">
        <li style="background-color: black"></li><form action="../backend/content_insert.php" method="post" enctype = "multipart/form-data">

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
            <div style="height: 300px; width: 250px; overflow: scroll">
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
        <li>
            <h3>Add season</h3>

            <label for="mediaID">Media ID:</label> <!-- TODO to change to a searchable list ? -->
            <input type="number" value=""
                   id="mediaID" name="mediaID">
            <label for="season">Season n°</label>
            <input type="number" value="1" min="1"
                   id="season" name="season">
            <label for="episode">Nb of episode (1 for film):</label>
            <input type="number" value="1" min="1"
                   id="episode" name="episode">
        </li>
        <li>
            <button class="w3-button w3-black">CREATE NEW SEASON TO A SERIE</button>

        <li style="background-color: black"></li>

        <!--SET MODERATOR-->
        <li>
            <h3>Set Moderator</h3>

            <label for="userID">Username:</label> <!-- TODO to change to a searchable list ? -->
            <input type="number" value=""
                   id="userID" name="userID">
        </li>
        <li>
            <button class="w3-button w3-black">CREATE NEW MODERATOR</button>
        </li>

        <li style="background-color: black"></li>

        <!--ADD VOICE ACTOR-->
        <li>
            <h3>Add Voice actor</h3>
            <label for="personID">Person ID:</label> <!-- TODO to change to a searchable list ? -->
            <input type="number" value=""
                   id="personID" name="personID">
        </li>
        <li>
            <button class="w3-button w3-black">CREATE NEW VOICE ACTOR</button>
        </li>

        <li style="background-color: black"></li>

        <!--ADD ROLE TO VOICE ACTOR-->
        <li>
            <h3>Add Voice actor role</h3>
            <label for="person_roleID">Person ID:</label> <!-- TODO to change to a searchable list ? -->
            <input type="number" value=""
                   id="person_roleID" name="person_roleID">
            <label for="media_roleID">Media ID:</label> <!-- TODO to change to a searchable list ? -->
            <input type="number" value=""
                   id="media_roleID" name="media_roleID">
        </li>
        <li>
            <button class="w3-button w3-black">CREATE NEW VOICE ACTOR</button>
        </li>

        <li style="background-color: black"></li>

        <!--ADD ANIMATION STUDIO-->
        <li>
            <h3>Add animation studio</h3>
            <label for="studio_name">name:</label>
            <br>
            <input type="text" value=""
                   id="studio_name" name="studio_name">
        </li>
        <li>
            <label for="studio_description">Description:</label>
            <br>
            <input type="text"
                   id="studio_description" name="studio_description">
        </li>
        <li>
            <label for="studio_picture">Picture:</label>
            <br>
            <input type="file"
                   id="studio_picture" name="studio_picture"
                   accept="image/png, image/jpeg">
        </li>
        <li>
            <button class="w3-button w3-black">CREATE NEW STUDIO</button>
        </li>

        <li style="background-color: black"></li>

        <!--ADD CATEGORIE-->
        <li>
            <h3>Add categorie</h3>
            <input type="text" value=""
                   id="categorie" name="categorie">
        </li>
        <li>
            <button class="w3-button w3-black">CREATE NEW CATEGORIE</button>
        </li>
    </ul>
</div>

</body>
</html>
