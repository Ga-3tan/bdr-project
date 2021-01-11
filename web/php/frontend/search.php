<?php
include '../includes/session_check.php';
include '../includes/header_nav.php';
?>

<div class="search-page">
    <div class="search-wrap">
        <div class="search">
            <input type="text" class="searchTerm" placeholder="What are you looking for?">
            <button type="submit" class="searchButton">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>

    <br>

    <div class="search-result">
        <ul class="w3-ul w3-hoverable w3-border">
            <a href="anime.php">
                <li class="w3-bar search-item" style="padding: 0;">
                    <img src="https://cdn.myanimelist.net/images/anime/1171/109222l.webp" class="w3-bar-item"
                         style="padding: 0; width: 120px">
                    <div class="w3-bar-item">
                        <span class="w3-large">Jujutsu Kaisen</span>
                        <br>
                        <span>TV</span>
                        <br>
                        <span>Score: 10</span>
                    </div>
                </li>
            </a>
            <a href="anime.php">
                <li class="w3-bar search-item" style="padding: 0;">
                    <img src="https://cdn.myanimelist.net/images/anime/1171/109222l.webp" class="w3-bar-item"
                         style="padding: 0; width: 120px">
                    <div class="w3-bar-item">
                        <span class="w3-large">Jujutsu Kaisen</span>
                        <br>
                        <span>TV</span>
                        <br>
                        <span>Score: 10</span>
                    </div>
                </li>
            </a>
        </ul>
    </div>

</div>
</body>
</html>
