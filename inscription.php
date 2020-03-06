<?php
    session_start();
    include('bd/connexionDB.php'); // Fichier PHP contenant la connexion à votre BDD

    // S'il y a une session alors on ne retourne plus sur cette page
    if (isset($_SESSION['id'])){
        header('Location: index.php'); 
        exit;
    }

    // Si la variable "$_Post" contient des informations alors on les traitres
    if(!empty($_POST)){
        extract($_POST);
        $valid = true;

        // On se place sur le bon formulaire grâce au "name" de la balise "input"
        if (isset($_POST['inscription'])){
            $nom  = htmlentities(trim($nom)); // On récupère le nom
            $prenom = htmlentities(trim($prenom)); // on récupère le prénom
            $mdp = trim($mdp); // On récupère le mot de passe 
            $mail = htmlentities(strtolower(trim($mail))); // On récupère le mail


            //  Vérification du nom
            if(empty($nom)){
                $valid = false;
                $er_nom = ("Le nom d' utilisateur ne peut pas être vide");
            }       

            //  Vérification du prénom
            if(empty($prenom)){
                $valid = false;
                $er_prenom = ("Le prenom d' utilisateur ne peut pas être vide");
            }       
             
            // Vérification du mail
            if(empty($mail)){
                $valid = false;
                $er_mail = "Le mail ne peut pas être vide";


            // On vérifit que le mail est dans le bon format
            }elseif(!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $mail)){
                $valid = false;
                $er_mail = "Le mail n'est pas valide";

            }else{
            // On vérifit que le mail est disponible
                $req_mail = $DB->query("SELECT mail FROM utilisateurs WHERE mail = ?",
                    array($mail));

                $req_mail = $req_mail->fetch();

                if ($req_mail['mail'] <> ""){
                    $valid = false;
                    $er_mail = "Ce mail existe déjà";
                }
            }

               
            
            // Vérification du mot de passe
            if(empty($mdp)) {
                $valid = false;
                $er_mdp = "Le mot de passe ne peut pas être vide";

        
            }

            // Si toutes les conditions sont remplies alors on fait le traitement
            if($valid){

                $mdp = crypt($mdp, "$6$rounds=5000$eiyofidosulfghidlk$");
                

                // On insert nos données dans la table utilisateur
                $DB->insert("INSERT INTO utilisateurs (nom, prenom, mail, mdp) VALUES 
                    (?, ?, ?, ?)", 
                    array($nom, $prenom, $mail, $mdp));

                header('Location: oui.php');
                exit;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="Stocker vos fichier à travers le temps...">
            <meta name="keywords" content="capsule temporelle, time capsul, time, capsule, futur, past, passé">
            <meta name="author" content="Julien">
            <meta name="google-site-verification" content="VjpbGAWMYq8V4zjdIZGtMtC6PzB47xJVNO_4uac3L18">
            <meta name="msvalidate.01" content="17EF287AB97DF82A56774594CABFED9B">
            <meta name="apple-mobile-web-app-title" content="Timecapsule">
            <meta property="og:title" content="Timecapsule">
            <meta property="og:description" content="Leave your mark in time !">
            <link rel="icon" href="icon/tc1.ico">
            <title>Time capsule</title>
            <!-- Bootstrap core CSS -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
            <!-- Index CSS -->
            <link href="css/index.css" rel="stylesheet">
        </head>
        <body class="bg-light">
    <div class="container">
  <div class="py-5 text-center">
    <a href="index.php"><img src="image/tc3.png" width="100" height="100" class="mb-4"/></a>
    <h2 style="color:#212529;">Inscription</h2>
    <p class="lead" style="color:#212529;"></p>
  </div>

  
    <div class="col-md-8 order-md-1">
      
      <form methos="POST"class="needs-validation" novalidate="">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="prenom">Prénom</label>
                          <?php
                          if (isset($er_prenom)){
                          ?>
                          <div><?= $er_prenom ?></div>
                          <?php   
                          }
                          ?>
                          <input type="text" placeholder="Votre prénom" class="form-control" name="prenom" value="<?php if(isset($prenom)){ echo $prenom; }?>" size="30" required="">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="nom">Nom</label>
                          <?php
                          // S'il y a une erreur sur le nom alors on affiche
                          if (isset($er_nom)){
                          ?>
                          <div><?= $er_nom ?></div>
                          <?php   
                          }
                          ?>
                        <input type="text" class="form-control" placeholder="Votre nom" name="nom" value="<?php if(isset($nom)){ echo $nom; }?>" size="30" required>
                      </div>
                    </div>
                      <hr class="mb-4">
                      <div class="mb-3">

                        <label for="mail">Email <span class="text-muted"></span></label>
                        <?php
                        if (isset($er_mail)){
                        ?>
                        <div><?= $er_mail ?></div>
                        <?php   
                        }
                        ?>
                        <input type="email" class="form-control" placeholder="Adresse mail" name="mail" size="30" value="<?php if(isset($mail)){ echo $mail; }?>" required>
                      </div>
                      <hr class="mb-4">
                      <form class="needs-validation" novalidate="">
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <label for="mdp">Mot de passe</label>
                          <?php
                          if (isset($er_mdp)){
                          ?>
                          <div><?= $er_mdp ?></div>
                          <?php   
                          }
                          ?>
                          <input type="password" class="form-control" placeholder="Mot de passe" size="30" name="mdp" value="<?php if(isset($mdp)){ echo $mdp; }?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label for="lastName">Confirmer le mot de passe</label>
                          <input type="password" class="form-control" placeholder="Confirmer le mot de passe" name="confmdp" required>
                        </div>
                      </div>
                        <hr class="mb-4">
                      
                        <button class="btn btn-primary btn-lg btn-block" type="submit" name="inscription">S'inscrire</button>
                  </form>
    </div>
  </div>

  <footer class="my-5 pt-5 text-muted text-center text-small">
                <p class="mb-1">© 2019-2021 Company Name</p>
                <ul class="list-inline">
                  <li class="list-inline-item"><a href="#">Privacy</a></li>
                  <li class="list-inline-item"><a href="#">CGU</a></li>
                  <li class="list-inline-item"><a href="#">Support</a></li>
                  </ul>
                  </footer>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="/docs/4.3/assets/js/vendor/jquery-slim.min.js"><\/script>')</script><script src="/docs/4.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
        <script src="form-validation.js"></script>

</body>
</html>