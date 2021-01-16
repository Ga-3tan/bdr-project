<?php
include '../includes/session_check.php';
include '../includes/header_nav.php';
include '../../../lib/model/dbConnect.php';

$db = new dbConnect();

if(isset($_GET['id'])) {
    $finishedList = $db->getListMedia($_GET['id'], 'Finished');
    $watchingList = $db->getListMedia($_GET['id'], 'Watching');
    $droppedList = $db->getListMedia($_GET['id'], 'Dropped');
    $planToWatch = $db->getListMedia($_GET['id'], 'Plan to watch');
}

function echoList ($list, $db) {
    if(isset($list)) {
        foreach ($list as $item) {
            $avg = $db->getAvgNote($item['idMedia']);
            $avgNote = (empty($avg)) ? 0 : round($avg[0]['moyenne']);
            echo '<li class="w3-bar" style="padding: 0;">
                    <a href="anime.php?id=' . $item['idMedia'] . '" class="list-elem">
                        <img src="../../img/covers/' . $item['image'] . '"
                             class="w3-bar-item"
                             style="padding: 0; width: 100px" alt="">
                        <div class="w3-bar-item">
                            <span class="w3-large">' . $item['media'] . '</span>
                            <br>
                            <span>' . $item['categorie'] . '</span>
                            <br>
                            <span>Score: ' . $avgNote. '</span>
                        </div>
                    </a>
                    <div class="w3-dropdown-hover w3-right">
                        <button class="w3-padding-small w3-button" title="More">
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="w3-dropdown-content w3-bar-block w3-card-4">
                            <ul class="w3-ul w3-hoverable">
                                <li>Watching</li>
                                <li>Plan to watch</li>
                                <li>Finished</li>
                                <li>Dropped</li>
                            </ul>
                        </div>
                    </div>
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
