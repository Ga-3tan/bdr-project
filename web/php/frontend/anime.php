<?php
include '../includes/session_check.php';
include '../includes/header_nav.php';
include '../../../lib/model/dbConnect.php';

// Gets the media data
if (!isset($_GET['id'])) {
    // No id, redirects
    header("Location: ../../../index.php");
    die();
}

$db = new dbConnect();
$data = $db->getMediaData($_GET['id']);

// Checks if result has data
if (empty($data)) {
    // Unknown anime, redirects
    header("Location: ../../../index.php");
    die();
}
$data = $data[0];

if (!file_exists("../../img/covers/" . $data['image'])) $data['image'] = "blank.jpg";
?>
<div style="width: 90%; min-width: 1000px; margin: 100px;">
    <div class="w3-col" style="width: 250px;">
        <img class="w3-border w3-padding w3-round" src="<?php echo '../../img/covers/' . $data['image'];?>"
             style="width: 100%;" alt="thumbnail">
        <ul class="w3-ul w3-border w3-round" style="margin-top: 10px">
            <li class="w3-center"><b>Informations</b></li>
            <?php
            echo '<li>Type: ' . $data['type'] . '</li>
                  <li>Category: ' . $data['categorie'] . '</li>
                  <li>Episodes: ' . $data['nbEpisodes'] . '</li>
                  <li>Release date: ' . $data['dateSortie'] . '</li>
                  <li>Duration: ' . $data['duree'] . '</li>
                  <li>Seasons: ' . $data['nbSaisons'] . '</li>';
            ?>

        </ul>
    </div>
    <div class="w3-rest">
        <div class="w3-row-padding" style="padding-left: 20px">
            <div class="w3-row" style="padding-left: 20px;">
                <?php echo '<h1>' . $data['titre'] . '</h1>'; ?>
            </div>
            <div class="w3-row w3-border w3-round" style="font-size: small;text-align: justify;">
                <p style="padding: 20px;"><?php echo $data['description']; ?></p>
            </div>

            <div class="w3-row" style="margin-top: 10px">
                <div class="w3-col" style="width: 150px; padding-right: 10px">
                    <ul class="w3-ul w3-border w3-round">
                        <li class="w3-center"><b>Add to list</b></li>
                        <ul class="w3-ul w3-hoverable">
                            <form class="w3-container" action="../backend/media_interact.php?id=<?php echo $_GET['id'] ?>" method="post">
                                <select class="w3-select" name="list_data">
                            <?php
                            if ($data['type'] == "Movie") {
                                echo '<option value="0">Plan to watch</option>
                                      <option value="1">Watching</option>';
                            } else {
                                echo '<option value="0">Plan to watch</option>
                                      <option value="1">Watching</option>
                                      <option value="2">Finished</option>
                                      <option value="3">Dropped</option>';
                            }
                            ?>
                                </select>
                                <button class="w3-btn" type="submit" value="Login" name="list_send">Send</button>
                            </form>
                        </ul>
                    </ul>
                </div>

                <div class="w3-col" style="width: 200px;">
                    <ul class="w3-ul w3-border w3-round">
                        <?php
                        // Gets the average note and the user note
                        $avgNote = $db->getAvgNote($_GET['id']);
                        if ($avgNote == null) $avgNote = "-";
                        $usrNote = $db->getUserNote($_SESSION['USER_USERNAME'], $_GET['id']);
                        if (empty($usrNote)) $usrNote = "-";

                        echo '<li class="w3-center"><b>Average note: ' . $avgNote . '/10</b></li>
                              <li class="w3-center"><b>Your note: ' . $avgNote . '</b></li>';
                        ?>
                        <form class="w3-container" action="../backend/media_interact.php?id=<?php echo $_GET['id'] ?>" method="post">
                            <select class="w3-select" name="note_data">
                                <option value="" disabled selected>your note</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        <li><button class="w3-btn" type="submit" value="Login" name="note_send">Send</button></li>
                        </form>
                        </li>
                    </ul>
                </div>
                <div class="w3-col w3-border w3-round w3-right"
                     style="min-width: 320px;width: 30%; height: 235px;overflow: auto">
                    <ul class="w3-ul">
                        <li><b>Voice actors</b></li>
                        <ul class="w3-ul w3-hoverable">
                            <?php
                            // Gets voice actors
                            $voiceActors = $db->getDubbers($_GET['id']);

                            foreach ($voiceActors as $actor) {
                                if (empty($actor['photoProfil']) || !file_exists("../../img/profiles/" . $actor['photoProfil']))
                                    $actor['photoProfil'] = "blank.jpg";

                                echo '<li class="w3-bar" style="padding: 0">
                                            <img src="' . "../../img/profiles/" . $actor['photoProfil'] . '"
                                                 class="w3-bar-item"
                                                 style="width:85px">
                                            <div class="w3-bar-item">
                                                <span class="w3-large">' . $actor['nom'] . ', ' . $actor['prenom'] . '</span>
                                            </div>
                                        </li>';
                            }
                            ?>
                        </ul>
                    </ul>
                </div>
                <div class="w3-col w3-rest" style="padding-top: 10px">
                    <ul class="w3-ul w3-border w3-round">
                        <li>
                            <form class="w3-container" action="../backend/media_interact.php?id=<?php echo $_GET['id'] ?>" method="post">
                                <label>Comments</label>
                                <input class="w3-input w3-round" type="text" name="comment_data">
                                <button class="w3-btn" type="submit" value="Login" name="comment_send">Send</button>
                            </form>
                        </li>
                        <ul class="w3-ul">
                            <?php
                            // Gets voice actors
                            $comments = $db->getComments($_GET['id']);

                            foreach ($comments as $c) {
                                echo '<li>
                                <div>' . $c['dateAjout'] . ', from : <a>' . $c['pseudo'] . '</a> </div>
                                <div>' . $c['commentaire'] . '</div>
                            </li>';
                            }
                            ?>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>
