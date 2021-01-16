<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/main_style.css">
</head>
<body>
<div class="w3-top">
    <div class="w3-bar w3-black w3-card">
        <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right"
           href="javascript:void(0)" title="Toggle Navigation Menu"><i
                class="fa fa-bars"></i></a>
        <a href="search.php?name=&send=Search&cat=0&stu=&ord=titre" class="w3-padding-large w3-hover-red w3-hide-small w3-left"><i
                class="fa fa-search"></i></a>
        <a href="../backend/sign_out.php" class="w3-bar-item w3-button w3-padding-large w3-right"><i class="fa fa-sign-out"></i></a>
        <a href="user.php" class="w3-bar-item w3-button w3-padding-large w3-right"><i class="fa fa-user"></i></a>
        <p class="w3-right" style="padding-right: 30px;"><?php echo $_SESSION['USER_USERNAME'] . " | ID : " . $_SESSION['USER_ID'] ?></p>
        <div class="w3-dropdown-hover">
            <button class="w3-padding-large w3-button" title="More" onclick="document.location = 'lists.php'">ANIME <i class="fa fa-caret-down"></i></button>
            <div class="w3-dropdown-content w3-bar-block w3-card-4">
                <a href="lists.php" class="w3-bar-item w3-button">MY LISTS</a>
                <a href="search.php?name=&send=Search&cat=0&stu=&ord=score" class="w3-bar-item w3-button">TOP ANIME</a>
                <a href="search.php?name=&send=Search&cat=0&stu=&ord=titre" class="w3-bar-item w3-button">SEARCH ANIME</a>
            </div>
        </div>
    </div>
</div>