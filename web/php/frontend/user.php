<?php
include '../includes/session_check.php';
include '../includes/header_nav.php';
?>

<div style="margin: 100px;">
    <img src="https://www.xovi.com/wp-content/plugins/all-in-one-seo-pack/images/default-user-image.png"
         style="width: 100px">
    <ul class="w3-ul w3-large">
        <li>
            firstname
        </li>
        <li>
            lastname
        </li>
        <li>birthday: <span>25.02.92</span></li>
        <li>
            username
        </li>
        <li>
            sexe: <span> male</span>
        </li>
        <li>
            email : <span>email@email.com</span>
        </li>
        <li>
            role : <span>moderator</span>
        </li>
        <li>
            <button class="w3-button w3-blue">edit</button>
        </li>
        <li>
            <form action="insert.php">
                <input class="w3-button w3-blue" type="submit" value="Add content" />
            </form>
        </li>
    </ul>
</div>

</body>
</html>
