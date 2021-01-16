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
$mediaCat = $db->getMediaCategories($_GET['id']);
if (!file_exists("../../img/covers/" . $data['image'])) $data['image'] = "blank.jpg";
?>
<div style="width: 90%; min-width: 1000px; margin: 100px;">
    <div class="w3-col" style="width: 250px;">
        <img class="w3-border w3-padding w3-round" src="<?php echo '../../img/covers/' . $data['image'];?>"
             style="width: 100%;" alt="thumbnail">
        <ul class="w3-ul w3-border w3-round" style="margin-top: 10px">
            <li class="w3-center"><b>Informations</b></li>
            <?php
            $ep =  $data['nbEpisodes'] == 0 ? '-' : $data['nbEpisodes'];
            $sea =  $data['nbSaisons'] == 0 ? '-' : $data['nbSaisons'];

            echo '<li>Media ID: ' . $data['id'] . '</li>';
            echo '<li>Type: ' . $data['type'] . '</li>';
            for ($i = 0; $i < count($mediaCat) && $i < 3; ++$i)
                echo '<li>Category ' . ($i+1) . ': ' . $mediaCat[$i]['tagCategorie'] . '</li>';
            if (count($mediaCat) > 3)
                echo '<li>(' . (count($mediaCat)-3) . ' more categories ...)</li>';
            echo '<li>Episodes: ' . $ep . '</li>
                  <li>Release date: ' . $data['dateSortie'] . '</li>
                  <li>Duration: ' . $data['duree'] . '</li>
                  <li>Seasons: ' . $sea . '</li>';
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
            <?php
            // Displays the add to list options if there are seasons
            if ($data['nbSaisons'] != 0) {
                echo '<div class="w3-row" style="margin-top: 10px">
                        <div class="w3-col" style="width: 150px; padding-right: 10px">
                            <ul class="w3-ul w3-border w3-round">
                                <li class="w3-center"><b>Add to list</b></li>
                                <ul class="w3-ul w3-hoverable">';

                echo '<form class="w3-container" action="../backend/media_interact.php?id=' . $_GET['id'] . '" method="post">';
                // Displays season dropdown
                echo '<select id="listId" onchange="changeNbEp()" class="w3-select" name="list_season">';

                $type = $data['type'] == 'Movie' ? 'Movie' : 'Season';
                for ($i = 1; $i <= $data['nbSaisons']; ++$i)
                    echo '<option value="' . $i . '">' . $type . ' ' . $i . '</option>';

                echo '</select>';

                // Sets the nb of watched episodes and list selection
                echo '<script>
                        function changeNbEp() {
                            document.getElementById(\'epNbId\').max = document.getElementById(\'s\' + document.getElementById(\'listId\').value).value;
                        }
                        function listSet() {
                            let item = document.getElementById(\'listSelect\').value;
                            let nbEp = document.getElementById(\'epNbId\');
                            if (item === \'2\') {
                                nbEp.value = document.getElementById(\'s\' + document.getElementById(\'listId\').value).value;
                                nbEp.disabled = true;
                            } else if (item === \'0\') {
                                nbEp.value = 0;
                                nbEp.disabled = true;
                            } else {
                                nbEp.disabled = false;
                            }
                        }
                        </script>';

                echo '<select id="listSelect" onchange="listSet()" class="w3-select" name="list_data">';
                // Displays the lists
                if ($data['type'] == "Movie") {
                    echo '<option value="0">Plan to watch</option>
                          <option value="2">Finished</option>';
                    echo '</select>';
                } else {
                    echo '<option value="0">Plan to watch</option>
                          <option value="1">Watching</option>
                          <option value="2">Finished</option>
                          <option value="3">Dropped</option>';
                    echo '</select>';

                    // Displays the nb episodes per season
                    for ($j = 1; $j <= $data['nbSaisons']; ++$j) {
                        echo '<param id="s' . $j-1 . '" value=' . $db->getMediaSeason($_GET['id'], $j)[0]['nbEpisodes'] . '>';
                    }
                    echo '<li><input onchange="changeNbEp()" disabled value="0" id="epNbId" type="number" style="width: 100%" name="list_episodes" min="0" max="0"></li>';
                }

                echo '
                      <li><button class="w3-btn" type="submit" value="Send" name="list_send">Send</button></li>
                        </form>
                        </ul>
                    </ul>
                </div>';
            }
            ?>

                <div class="w3-col" style="width: 200px;">
                    <ul class="w3-ul w3-border w3-round">
                        <?php
                        // Gets the average note and the user note
                        $avg = $data['score'];
                        $avgNote = empty($avg) ? '-' : $avg;
                        $note = $db->getUserNote($_SESSION['USER_USERNAME'], $_GET['id']);
                        $usrNote = empty($note) || $note[0] == null ? '-' : $note[0]['note'];

                        echo '<li class="w3-center"><b>Average note: ' . ($avgNote == '-' ? $avgNote : round($avgNote)) . '/10</b></li>
                              <li class="w3-center"><b>Your note: ' . ($usrNote == '-' ? $usrNote : floor($usrNote)) . '</b></li>';
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
                        <li><button class="w3-btn" type="submit" value="Send" name="note_send">Send</button></li>
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
                            $voiceActors = $db->getMediaDubbers($_GET['id']);

                            foreach ($voiceActors as $actor) {
                                if (empty($actor['photoProfil']) || !file_exists("../../img/profiles/" . $actor['photoProfil']))
                                    $actor['photoProfil'] = "blank.jpg";

                                echo '<li class="w3-bar" style="padding: 0">
                                            <img src="' . "../../img/profiles/" . $actor['photoProfil'] . '"
                                                 class="w3-bar-item"
                                                 style="width:85px">
                                            <div class="w3-bar-item">
                                                <span class="w3-large">' . $actor['nom'] . ', ' . $actor['prenom'] . '</span><br>
                                                <span class="w3-large">ID : ' . $actor['id'] . '</span>
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
