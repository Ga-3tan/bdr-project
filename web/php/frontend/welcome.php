<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/main_style.css">
    <script src="../../js/check_registration.js"></script>
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
            <form class="form login form-left" action="../backend/check_login.php" method="post">
                <input type="text" placeholder="username" name="username"><br>
                <input type="password" placeholder="password" name="password"><br>
                <input type="submit" value="Login">
            </form>
        </div>
        <div class="form-container w3-col">
            <h1>Register</h1>
            <form class="form register form-right" onsubmit="return validateRegistration()" action="../backend/check_registration.php" method="post">
                <input type="text" placeholder="username" name="reg_username"><br>
                <input type="text" placeholder="firstname" name="reg_firstname"><br>
                <input type="text" placeholder="lastname" name="reg_lastname"><br>
                <input type="text" placeholder="email" name="reg_email"><br>
                <div style="font-size: 10px">Format : xxx@yyy.zzz</div>
                <input type="password" placeholder="password" name="reg_password"><br>
                <div style="font-size: 10px">Minimum 4 alpha numeric characters</div>
                <input type="submit" value="Login" name="reg_send" onclick="clearErrors()">
            </form>
        </div>
    </div>
</div>
</body>
</html>
