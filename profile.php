<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Mon profil</h1>

    <?php 
        if(isset($_SESSION["user"])) {
            $infosSession = $_SESSION["user"];
        } else {
            // on verra plus tard
        }  
    ?>

    <p>Pseudo : <?= $infosSession["pseudo"] ?></p>
    <p>Email : <?= $infosSession["email"] ?></p>


    
</body>
</html>