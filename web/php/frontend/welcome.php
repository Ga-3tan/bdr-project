<?php
session_start();

if (isset($_SESSION['USER_LOGGED'])) {
    header("Location: lists.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/main_style.css">
    <script src="../../js/check_login_registration.js"></script>
</head>
<body>
<div class="bg-image"></div>
<div class="bg-bloc">
    <div class="bg-form bg-top">
        <h1>Anime list</h1>
        <p>please <b>log in</b> or <b>register</b></p>
    </div>
    <div class="bg-form bg-bottom w3-row">
        <div class="form-container w3-col">
            <h1>Login</h1>
            <form class="form register form-right" onsubmit="return validateLogin()" action="../backend/check_login.php" method="post">
                <?php
                // If there was an error (username taken)
                if (isset($_SESSION['WRONG_LOGIN'])) {
                    $username = $_SESSION['WRONG_LOGIN']['login_username'];
                    $password = $_SESSION['WRONG_LOGIN']['login_password'];

                    echo '<div style="font-size: 10px">Username or password is incorrect</div>
                          <input type="text" style="border: solid 1px red" placeholder="username" name="login_username" value="' . $username . '">
                          <input type="password" placeholder="password" name="login_password" value="' . $password . '"><br>';

                    unset($_SESSION['WRONG_LOGIN']);
                } else { // No error
                    echo '<input type="text" placeholder="username" name="login_username"><br>
                          <input type="password" placeholder="password" name="login_password"><br>';
                }
                ?>
                <input type="submit" value="Login" name="login_send" onclick="clearErrors()">
            </form>
        </div>
        <div class="form-container w3-col">
            <h1>Register</h1>
            <form class="form register form-right" onsubmit="return validateRegistration()" action="../backend/check_registration.php" method="post">
                <?php
                // If there was an error (username taken)
                if (isset($_SESSION['TAKEN_USERNAME'])) {
                    $username = $_SESSION['TAKEN_USERNAME']['reg_username'];
                    $firstname = $_SESSION['TAKEN_USERNAME']['reg_firstname'];
                    $lastname = $_SESSION['TAKEN_USERNAME']['reg_lastname'];
                    $email = $_SESSION['TAKEN_USERNAME']['reg_email'];
                    $password = $_SESSION['TAKEN_USERNAME']['reg_password'];

                    echo '<input type="text" style="border: solid 1px red" placeholder="username" name="reg_username" value="' . $username . '"><span class="reg_error"></span><br>
                          <div style="font-size: 10px">This username is already taken !</div>
                          <input type="text" placeholder="firstname" name="reg_firstname" value="' . $firstname . '"><br>
                          <input type="text" placeholder="lastname" name="reg_lastname" value="' . $lastname . '"><br>
                          <input type="text" placeholder="email" name="reg_email" value="' . $email . '"><br>
                          <div style="font-size: 10px">Format : xxx@yyy.zzz</div>
                          <input type="password" placeholder="password" name="reg_password" value="' . $password . '"><br>
                          <div style="font-size: 10px">Minimum 4 alpha numeric characters</div>';

                    unset($_SESSION['TAKEN_USERNAME']);
                } else { // No error
                    echo '<input type="text" placeholder="username" name="reg_username"><br>
                          <input type="text" placeholder="firstname" name="reg_firstname"><br>
                          <input type="text" placeholder="lastname" name="reg_lastname"><br>
                          <input type="text" placeholder="email" name="reg_email"><br>
                          <div style="font-size: 10px">Format : xxx@yyy.zzz</div>
                          <input type="password" placeholder="password" name="reg_password"><br>
                          <div style="font-size: 10px">Minimum 4 alpha numeric characters</div>';
                }
                ?>
                <input type="submit" value="Register" name="reg_send" onclick="clearErrors()">
            </form>
        </div>
    </div>
</div>
</body>
</html>
