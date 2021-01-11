<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/search.css">
</head>
<body>
<div class="w3-top">
    <div class="w3-bar w3-black w3-card">
        <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right"
           href="javascript:void(0)" onclick="myFunction()" title="Toggle Navigation Menu"><i
                class="fa fa-bars"></i></a>
        <a href="search.html" class="w3-padding-large w3-hover-red w3-hide-small w3-left"><i
                class="fa fa-search"></i></a>
        <a href="" class="w3-bar-item w3-button w3-padding-large w3-right"><i class="fa fa-user"></i></a>
        <div class="w3-dropdown-hover">
            <button class="w3-padding-large w3-button" title="More">ANIME <i class="fa fa-caret-down"></i></button>
            <div class="w3-dropdown-content w3-bar-block w3-card-4">
                <a href="lists.php" class="w3-bar-item w3-button">MY LISTS</a>
                <a href="search.php" class="w3-bar-item w3-button">TOP ANIME</a>
                <a href="search.php" class="w3-bar-item w3-button">SEARCH ANIME</a>
            </div>
        </div>
    </div>
</div>

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
            <a href="anime.html">
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
            <a href="anime.html">
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
