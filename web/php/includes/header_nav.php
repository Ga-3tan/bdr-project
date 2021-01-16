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
    <div class="w3-bar w3-black">
        <a href="search.php?name=&send=Search&cat=0&stu=&ord=titre" class="w3-padding-large w3-hover-red w3-hide-small w3-left"><i
                class="fa fa-search"></i></a>
        <a href="../backend/sign_out.php" class="w3-bar-item w3-button w3-padding-large w3-right"><i class="fa fa-sign-out"></i></a>
        <a href="user.php" class="w3-bar-item w3-button w3-padding-large w3-right"><i class="fa fa-user"></i></a>
        <a class="w3-right w3-bar-item w3-padding-large"><?php echo $_SESSION['USER_USERNAME'] . " | ID : " . $_SESSION['USER_ID'] ?></a>
        <a href="lists.php" class="w3-bar-item w3-button w3-padding-large">MY LISTS</a>
        <a href="search.php?name=&send=Search&cat=0&stu=&ord=score" class="w3-bar-item w3-button w3-padding-large">TOP ANIME</a>
        <a href="search.php?name=&send=Search&cat=0&stu=&ord=titre" class="w3-bar-item w3-button w3-padding-large">SEARCH ANIME</a>
    </div>
</div>