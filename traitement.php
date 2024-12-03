<?php

if(isset($_GET["action"])) {

    switch($_GET["action"]) {

        case "register": // dans forum, c'est une méthode d'un controleur (le security controler aura une fonction register() )

            // on s'assure que le formulaire de register.php a bien été envoyé
            if($_POST["submit"]) {

                // connexion à la base de données
                $pdo = new PDO("mysql:host=localhost; dbname=php_hash_colmar; charset=utf-8", "root", "");

                // filtrer la saisie des champs du formulaire d'inscription
                $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS); // "peudo" = nom de l'élément que je souhaite filtrer -> dans input : on récupère bien name = 'pseudo', et non l'id = 'pseudo' ! 
                // $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL);
                $pass1 = filter_input(INPUT_POST, "pass1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $pass2 = filter_input(INPUT_POST, "pass2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);


                if($pseudo && $email && $pass1 && $pass2) {
                    var_dump("ok"); die; // si les filtres passent, on affichera ok. sinon page blanche
                    $requete = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                    $requete->execute([":email" => $email]);
                    $user = $requete->fetch();

                    // si utilisateur existe (autrement dit, si $requete->fetch() nous renvoie bien une ligne), on ne veut pas l'inscrire dans notre BDD
                    if($user) {
                        header("Location : register.php"); exit;
                    } else {
                        // var_dump("utilisateur inexistant"); die;
                        // insertion de l'utilisateur en base de données car il n'existe pas encore
                        if(($pass1 == $pass2) AND (strlen($pass1) >= 5)) {
                            $insertUser = $pdo->prepare("INSERT INTO user (pseudo, email, password) VALUES (:pseudo, :email, :password)");
                            $insertUser->execute([
                                ":pseudo" => $pseudo,
                                ":email" => $email,
                                ":password" => password_hash($pass1, PASSWORD_DEFAULT), // on envoie le mot de passe haché pour stocker en BDD !   
                            ]);
                            header("Location : login.php"); exit; // si on est bien enregistré, on est naturellement redirigé vers login, pour pouvoir se 'loguer' sur le site
                        } else {
                            // message "Les mots de passe ne sont pas identiques ou mot de passe trop court ! 
                        }

                    }

                } else {
                    // problème de saisie dans les champs du formulaire
                }
                    
            }

            // par défaut, j'affiche le formulaire d'inscription
            header("location : register.php"); exit;
            
        break;

        

        case "login" :

            // connexion à l'application

            // on s'assure que le formulaire de login.php a bien été envoyé
            if($_Post["submit"]) {

                // connexion à la base de données
                $pdo = new PDO("mysql:host=localhost; dbname=php_hash_colmar; charset=utf-8", "root", "");

                // on filtre les champs du formulaire (faille XSS)
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL); // contre faille XSS
                $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS); // contre faille XSS

                // je vérifie que les filtres sont valides
                if($email AND $password) {
                    $requete = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                    $requete->execute([":email" => $email]); // requête préparée pour lutrer contre l'injection SQL
                    $user = $requete->fetch();
                    // var_dump($user); die; -> on vérifie que tout fonctionne bien : on récupère un tableau avec les infos de l'utilisateur s'il existe ou on récupère un false si l'utilisateur n'existe pas.

                    // est-ce que l'utilisateur existe ou non
                    if($user) {

                        // on récupère le password de la BDD
                        $hash = $user["password"];

                        // le if dessous renvoie un booléern : true si ça matche, false si ça ne matche pas
                        if(password_verify($password, $hash)) { // on compare $password (mot de passe saisi dans le formulaire) et $hash (stocké en base de données)

                            $_SESSION["user"] = $user; // je stocke en session l'intégralité des données du user -> l'utilisateur est donc connecté

                            header("location : home.php"); exit; // dans forum : ("location : index.php?ctrl=home&action=index&id=x") -> controlleur s'appelle home et la méthode s'apelle index et l'id a le numéro x
                            // quand tout se passe bien, il nous redirige sur la page d'accueil - c'est le seul cas sinon partout ailleurs, il nous redirige sur login.php

                        } else {
                            header("location : login.php"); exit;
                            // message utilisateur inconnu ou mot de passe incorrect -> assez vague pour ne pas aider un hacker ! 
                        }

                    } else {
                        header("location : login.php"); exit;
                        // message utilisateur inconnu ou mot de passe incorrect -> assez vague pour ne pas aider un hacker !
                    }

                }

            }

            header("location : login.php"); exit;


        break; // comme tjrs, à la fin de chaque case, un break; pour arrêter l'exécution du script. 


        case "profile" :

            header("location : profile.php"); exit;

        break;
        
        
        
        
        case "logout" : 

            // déconnexion à l'application
            unset($_SESSION["user"]); // on supprime tout le tableau user qui est dans la globale $_SESSION -> cela revient à déconnecter l'utilisateur
            header("location : home.php"); exit;

        break;

    }
}