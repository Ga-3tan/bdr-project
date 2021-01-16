<?php
include '../includes/session_check.php';
include '../includes/header_nav.php';
include "../../../lib/model/dbConnect.php";

$db = new dbConnect();

// Gets all categories and studios
$categoryData = $db->getAllCategories();
$studioData   = $db->getAllStudios();
?>

<form action="search.php" method="get" enctype="multipart/form-data">
<div class="search-page">
    <div class="search-wrap">
        <div class="search">
            <?php
            $selected = '';
            if (isset($_GET['name'])) $selected = $_GET['name'];
            ?>
            <input type="text" class="searchTerm" placeholder="What are you looking for?" value="<?php echo $selected; ?>" name="name">
            <button type="submit" class="searchButton" value="Search" name="send">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <div class="w3-container w3-margin-top">
            <div class="w3-panel w3-black"><b>Search options</b></div>
            <select class="w3-select" name="cat">
                <option value="" selected>All categories</option>
                <?php
                foreach ($categoryData as $c) {
                    $selected = '';
                    if (isset($_GET['cat']) && $_GET['cat'] == $c['tag']) $selected = 'selected';
                    echo '<option ' . $selected . ' value="' . $c['tag'] . '">' . $c['tag'] . '</option>';
                }

                ?>
            </select>

            <select class="w3-select" name="stu">
                <option value="" selected>All studios</option>
                <?php
                foreach ($studioData as $s) {
                    $selected = '';
                    if (isset($_GET['stu']) && $_GET['stu'] == $s['nom']) $selected = 'selected';
                    echo '<option ' . $selected . ' value="' . $s['nom'] . '">' . $s['nom'] . '</option>';
                }
                ?>
            </select>

            <select class="w3-select" name="type">
                <option value="all" <?php echo isset($_GET['type']) && $_GET['type'] == 'all' ? 'selected' : ''; ?>>All types</option>
                <option value="serie" <?php echo isset($_GET['type']) && $_GET['type'] == 'serie' ? 'selected' : '';?>>Series only</option>
                <option value="movie" <?php echo isset($_GET['type']) && $_GET['type'] == 'movie' ? 'selected' : '';?>>Movies only</option>
            </select>

            <select class="w3-select" name="ord">
                <option value="titre" <?php echo isset($_GET['ord']) && $_GET['ord'] == 'titre' ? 'selected' : ''; ?>>Order by name</option>
                <option value="score" <?php echo isset($_GET['ord']) && $_GET['ord'] == 'score' ? 'selected' : ''; ?>>Order by score</option>
            </select>
        </div>
        <div class="w3-panel w3-black"><b>Results</b></div>
    </div>


</form>
    <br>
    <div class="search-result">
        <ul class="w3-ul w3-hoverable w3-border">
            <?php
            // Gets the search results
            if (isset($_GET['name'])) {
                $results = $db->searchMedia($_GET['name'], $_GET['cat'], $_GET['stu'], $_GET['ord'], $_GET['type']);

                foreach ($results as $res) {
                    echo '<li class="w3-bar w3-animate-opacity" style="padding: 0;">
                    <a href="anime.php?id=' . $res['id'] . '" class="list-elem">
                        <img src="../../img/covers/' . $res['image'] . '"
                             class="w3-bar-item"
                             style="padding: 0; width: 100px" alt="">
                        <div class="w3-bar-item">
                            <span class="w3-large">' . $res['titre'] . '</span>
                            <br>
                            <span>' . $res['type'] . '</span>
                            <br>';
                    if ($res['type'] == 'Serie')
                        echo '<span>' . round($res['nbSaisons']) . ' seasons</span>
                            <br>';
                      echo '<span>Score: <b>' . round($res['score']) . '</b></span>
                            <br>
                            <span>ID: ' . $res['id'] . '</span>
                        </div>
                    </a>';
                }
            }
            ?>
        </ul>
    </div>

</div>
</body>
</html>
