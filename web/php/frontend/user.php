<?php
/*
 -------------------------------------------------------------------------------------
 Projet BDR
 File        : user.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 11.01.2021
 Goal        : Contains all the user data and a form to update it

 Comment(s) : -
 ------------------------------------------------------------------------------------
*/

include '../includes/session_check.php';
include '../includes/header_nav.php';
include "../../../lib/model/dbConnect.php";

// Gets the user data
$db = new dbConnect();
$userData = $db->getUserData($_SESSION['USER_USERNAME'])[0];

if (!file_exists("../../img/profiles/" . $userData['photoProfil']) || $userData['photoProfil'] == NULL)
    $userData['photoProfil'] = "blank.jpg";
?>

<form action="../backend/profile_update.php" method="post" enctype="multipart/form-data">
<div style="margin: 100px;">
    <?php
    if (isset($_SESSION["UPDATE_FAIL"])) {
        echo '<div class="w3-panel w3-red w3-animate-opacity"><h3>' . $_SESSION["UPDATE_FAIL"] . '</h3></div>';
        unset($_SESSION["UPDATE_FAIL"]);
    } else if (isset($_SESSION["UPDATE_SUCCESS"])) {
        echo '<div class="w3-panel w3-green w3-animate-opacity"><h3>' . $_SESSION["UPDATE_SUCCESS"] . '</h3></div>';
        unset($_SESSION["UPDATE_SUCCESS"]);
    }
    ?>

    <img <?php echo 'src="../../img/profiles/' . $userData['photoProfil'] . '"'; ?>
         style="width: 100px">
    <input type="file"
           id="profilePicture" name="update_picture"
           accept="image/png, image/jpeg">
    <ul class="w3-ul w3-large">
        <li>First name
            <input type="text" id="firstname" maxlength="50" class="w3-input w3-border"
                   name="update_firstname" value="<?php echo $userData['prenom']; ?>" required>
        </li>
        <li>Last name
            <input type="text" id="lastname" maxlength="50" class="w3-input w3-border"
                   name="update_lastname" value="<?php echo $userData['nom']; ?>" required>
        </li>
        <li>Birthday<span><input type="date" id="start" name="update_birthdate"
                                   min="1900-01-01" max="2999-12-31" class="w3-input w3-border"
                                   value="<?php echo $userData['dateNaissance']; ?>" required></span></li>
        <li>
            <input type="text" id="username" maxlength="60" class="w3-input w3-border"
                   name="update_username" value="<?php echo $userData['pseudo']; ?>" required>
        </li>
        <li>
            Gender<span>
                <select id="updateGender" name="update_gender" class="w3-select w3-border">
                    <option value="homme" <?php echo $userData['sexe'] == 'homme' ? 'selected' : ''; ?> >Man</option>
                    <option value="femme" <?php echo $userData['sexe'] == 'femme' ? 'selected' : ''; ?> >Woman</option>
                    <option value="autre" <?php echo $userData['sexe'] == 'autre' ? 'selected' : ''; ?>>Other</option>
                </select>
        </li>
        <li>
            Email<span><input type="email" id="email"
                                 name="update_email" class="w3-input w3-border"
                                 value="<?php echo $userData['email']; ?>" required></span>
        </li>
        <li>
            Account type : [<span><?php echo $userData['moderateur'] ? 'Moderator' : 'User'; ?>]</span>
        </li>
        <li>
            <button class="w3-button w3-blue" type="submit" value="Send" name="update_send">Submit modifications</button>
        </li>
        </form>
        <li style="background-color: black"></li>
        <?php
        if ($userData['moderateur']) {
            echo '<li>Moderator options
                    <form action="admin_panel.php">
                        <input class="w3-button w3-blue" type="submit" value="Add content in database" />
                    </form>
                </li>';
        }
        ?>

    </ul>
</div>

</body>
</html>
