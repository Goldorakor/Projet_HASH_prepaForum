<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


    <?php

        // ici, on fabrique un genre de navbar, juste pour montrer le principe
        // on ne le fera pas partout car ce serait fastidieux

        // isset vérifie l'existence d'une variable : on vérifie l'existence du tableau user dans ma session
        // ça veut dire : si je suis connecté
        if(isset($_SESSION["user"])) { // "user" car dans traitement.php, on a décidé que $_SESSION["user"] = $user; on peut prendre $_SESSION["bidule"] = $user; -> if(isset($_SESSION["bidule"])) ?>
        
            <a href="traitement.php?action=logout">Se déconnecter</a>
            <a href="traitement.php?action=profile">Mon profil</a>

        <?php } else { ?>

            <a href="traitement.php?action=login">Se connecter</a>
            <a href="traitement.php?action=register">S'inscrire</a>

        <?php } ?>


   <h1>ACCUEIL</h1>

    <?php 
        if(isset($_SESSION["user"])) {
            echo "<p>Bienvenue ".$_SESSION["user"]["pseudo"]."<p>"; // $_SESSION["user"] est un tableau, dans lequel je peux venir piocher des informations (par exemple le pseudo de l'utilisateur connecté)
        }

   ?>

</body>
</html>