<?php
/*
 -------------------------------------------------------------------------------------
 Projet BDR
 File        : lists.php
 Author(s)   : Zwick Gaétan, Ngueukam Djeuda Wilfried Karel, Maziero Marco
 Date        : 11.01.2021
 Goal        : This is were all the user lists are displayed

 Comment(s) : -
 ------------------------------------------------------------------------------------
*/

include '../includes/session_check.php';
include '../includes/header_nav.php';
include '../../../lib/model/dbConnect.php';

$db = new dbConnect();

if(isset($_SESSION['USER_ID'])) {
    echo "<script>console.log('Fetch data from DB.');</script>";
    $finishedList = $db->getListMedia($_SESSION['USER_ID'], 'Finished');
    $watchingList = $db->getListMedia($_SESSION['USER_ID'], 'Watching');
    $droppedList = $db->getListMedia($_SESSION['USER_ID'], 'Dropped');
    $planToWatch = $db->getListMedia($_SESSION['USER_ID'], 'Plan to watch');
}

function echoList ($list, $db) {
    if(isset($list)) {
        foreach ($list as $item) {
            $avg = $db->getAvgNote($item['idMedia']);
            $avgNote = (empty($avg)) ? 0 : round($avg[0]['moyenne']);
            echo '<li class="w3-bar w3-animate-top" style="padding: 0;">
                    <a href="anime.php?id=' . $item['idMedia'] . '" class="list-elem">
                        <img src="../../img/covers/' . $item['image'] . '"
                             class="w3-bar-item"
                             style="padding: 0; width: 100px" alt="">
                        <div class="w3-bar-item">
                            <span class="w3-large">' . $item['media'] . '</span>
                            <br>';
                        if ($item['categorie'] == 'serie')
                            echo '<span class="w3-large"><i>(Season ' . $item['saison'] . ')</i></span>
                                  <span>Ep. watched:<b> ' . $item['nbEpisodesVus'] . '</b></span><br>';
                      echo '<span>' . ($item['categorie'] == 'serie' ? 'Serie' : 'Movie') . '</span>
                            <br>
                            <span>Score: <b>' . $avgNote. '</b></span>
                            <br>
                            <span>ID: ' . $item['idMedia'] . '</span>
                        </div>
                    </a>
                </li>';
        }
    }
}
?>
<div class="list-page">
<div class="list-wrap">
    <div class="list-row w3-row-padding">
        <div class="list-col w3-col w3-quarter">
            <h2>Watching</h2>
            <div class="list w3-border">
                <ul class="w3-ul w3-hoverable" style="overflow: auto; height: 100%">
                    <?php echoList($watchingList, $db);?>
                </ul>
            </div>
        </div>
        <div class="list-col w3-col w3-quarter">
            <h2>Plan to watch</h2>
            <div class="list w3-border">
                <ul class="w3-ul w3-hoverable" style="overflow: auto; height: 100%">
                    <?php echoList($planToWatch, $db);?>
                </ul>
            </div>
        </div>
        <div class="list-col w3-col w3-quarter">
            <h2>Finished</h2>
            <div class="list w3-border">
                <ul class="w3-ul w3-hoverable" style="overflow: auto; height: 100%">
                    <?php echoList($finishedList, $db); ?>
                </ul>
            </div>
        </div>
        <div class="list-col w3-col w3-quarter">
            <h2>Dropped</h2>
            <div class="list w3-border">
                <ul class="w3-ul w3-hoverable" style="overflow: auto; height: 100%">
                    <?php echoList($droppedList, $db);?>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>
