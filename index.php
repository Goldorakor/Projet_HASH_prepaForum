<?php
session_start(); // obligé d'appeler cette méthode car on se sert de la session plus bas.

$password = "monMotdePasse1234";
$password2 = "monMotdePasse1234";


// hash est une fonction PHP qui permet de préciser l'algorithme utilisé (argument 1)et le password à hacher (argument 2).
// $md5 contient la versionb md5 de notre password
$md5 = hash('md5', $password);
$md5_2 = hash('md5', $password2);
echo $md5."<br>";
echo $md5_2."<br>";
// une version hachée avec md5 d'un mot de passe ne change pas à chaque rechargement ... embêtant !
// un même mot de passe dans deux variables différentes génère exactement la même chaine de caractères ... embêtant ! 



$sha256 = hash('sha256', $password);
$sha256_2 = hash('sha256', $password2);
echo $sha256."<br>";
echo $sha256_2."<br>";
// chaine plus longue mais la problématique reste la même ... les deux mêmes problèmes que précédemment
// --> algorithme de hachage dit "faible"





// ALGORITHME DE HACHAGE FORT

$hash = password_hash($password, PASSWORD_DEFAULT); // PASSWORD_DEFAULT choisit l'algorithme le plus performant du moment
$hash_2 = password_hash($password2, PASSWORD_DEFAULT);
echo $hash."<br>"; // si on raffraichit la page, le mot de passe change (le 'salt' et la partie qui correspond au hachage du mot de passe)
echo $hash_2."<br>";
// deux chaines différentes et qui changent chacune à chaque raffraichissement : salt et hash aléatoires.


echo "<br><br><br><br>";


// saisie dans le formulaire de login
$saisie = "monMotdePasse1234"; // rappel : on utilise <input type="password"> dans un formulaire pour un champ d'entrée de mot de passe

$check = password_verify ($saisie, $hash);
// $saisie = le mot de passe utilisateur
// $hash = un hachage créé par la fonction password_hash()
var_dump($check); // booléen = true

$user = "Michael"; // le $user ne sera pas juste une chaîne de caractères mais un objet contenant bon nombre d'informations (pseudo, mail, date d'inscription, etc)

$check2 = password_verify ($saisie, $hash_2);
var_dump($check2); // booléen = true

// $hash et $hash_2 ne sont pas similaires mais les deux fonctionnent pourtant !  ... on ne compare pas bêtement des chaines de caractères avec password_verift, c'est une autre opération



// pour une opération de connexion à notre application : 
if (password_verify($saisie, $hash)) {
    echo "Les mots de passe correspondent !";
    $_SESSION["user"] = $user;
    // si $_SESSION["user"] n'existe pas, c'est que l'utilisateur est déconnecté
    echo $user." est connecté !";
} else {
    echo "Les mots de passe sont différents !";
}


/* 

remarques sur les bdd :

dans une table, 
symbole clé dorée : clé primaire de la table (une seule possible)
symbole clé verte : clé étrangère de la table (plusieurs possibles)
symbole clé rouge : index de clé unique -> exemple : colonne email - on ne peut pas avoir le même émail 2 fois dans la colonne email

*/







