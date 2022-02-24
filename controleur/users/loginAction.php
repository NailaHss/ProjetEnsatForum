<?php
session_start();
require('../model/database.php');

//Validation du formulaire
//Validation du formulaire
if(isset($_POST['validate'])){

    //Vérifier si l'user a bien complété tous les champs
    if(!empty($_POST['pseudo']) AND !empty($_POST['password'])){
        
        //Les données de l'user
     $user_pseudo = htmlspecialchars($_POST['pseudo']);
     $user_password = htmlspecialchars($_POST['password']);
      if( $user_pseudo=="admin" AND $user_password=="123abc" ){
          
        header('Location: ../vues/admin.php');
      }
           

        //Vérifier si l'utilisateur existe (si le pseudo est correct)
        $checkIfUserExists = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');
        $checkIfUserExists->execute(array($user_pseudo));

        if($checkIfUserExists->rowCount() > 0){
            
            //Récupérer les données de l'utilisateur
            $usersInfos = $checkIfUserExists->fetch();

            //Vérifier si le mot de passe est correct
            if(password_verify($user_password, $usersInfos['mdp'])){
            
                //Authentifier l'utilisateur sur le site et récupérer ses données dans des variables globales sessions
                $_SESSION['auth'] = true;
                $_SESSION['id'] = $usersInfos['id'];
                $_SESSION['lastname'] = $usersInfos['nom'];
                $_SESSION['firstname'] = $usersInfos['prenom'];
                $_SESSION['email'] = $usersInfos['email'];
                $_SESSION['cl'] = $usersInfos['compte_linkdlen'];
                $_SESSION['num'] = $usersInfos['numero'];
                $_SESSION['desc'] = $usersInfos['description'];
                $_SESSION['daten'] = $usersInfos['datenaissance'];
               $_SESSION['pseudo'] = $usersInfos['pseudo'];
                //Rediriger l'utilisateur vers la page d'accueil
                header('Location: ../vues/index.php');
    
            }else{
                $errorMsg = "Votre mot de passe est incorrect...";
            }

        }else{
            $errorMsg = "Votre pseudo est incorrect...";
        }

    }else{
        $errorMsg = "Veuillez compléter tous les champs...";
    }

}