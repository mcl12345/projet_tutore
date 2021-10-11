<?php

session_start();

function print_LOGO_FORMSEARCH_MENU($db_host_, $db_name_, $db_user_, $db_password_) {

    $bool_verification_role = false;
    try {
        $pdo = new PDO("mysql:host=".$db_host_.";dbname=".$db_name_, $db_user_, $db_password_);
        $stmt = $pdo->prepare("SELECT * FROM user where username = ?");
        if($stmt->execute(array($_COOKIE["the_username"]))) {
            while($row = $stmt->fetch()) {
                if($row['role'] == "administrateur") {
                    $bool_verification_role = true;
                }
            }
        }
    } catch(PDOException $e) {
       echo $sql . "<br>" . $e->getMessage();
    }

  echo '<!DOCTYPE html>
  <head>
    <title>Plateforme musicale</title>
    <meta charset="UTF-8">
    <meta name="description" content="Plateforme musicale">
    <meta name="keywords" content="musique">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="../">Plateforme musicale</a>
            </div>
            <ul class="nav navbar-nav">';

    if(basename($_SERVER['PHP_SELF']) == "index.php")  {
        echo '<li class="active">';
    } else {
        echo '<li>';
    }
   echo '<a href="../">Accueil</a>
        </li>
   <li class="dropdown">
         <a class="dropdown-toggle" data-toggle="dropdown" href="#">Listes
         <span class="caret"></span></a>
         <ul class="dropdown-menu">';
             if(basename($_SERVER['PHP_SELF']) == "liste_likes/index.php")  {
               echo '<li class="active">';
             } else {
               echo '<li>';
             }
             echo '<a href="../liste_likes/">Liste des morceaux aimés</a>
             </li>';
             if(basename($_SERVER['PHP_SELF']) == "liste_recente/index.php")  {
               echo '<li class="active">';
             } else {
               echo '<li>';
             }
             echo '<a href="../liste_recente/">Liste de morceau(x) écouté(s) récemment</a>
             </li>';
             if(basename($_SERVER['PHP_SELF']) == "liste_genre/index.php")  {
               echo '<li class="active">';
             } else {
               echo '<li>';
             }
             echo '<a href="../liste_genre/">Liste par genre</a>
             </li>';
   echo '</ul>
   </li>';
   if(basename($_SERVER['PHP_SELF']) == "about/index.php")  {
     echo '<li class="active">';
   } else {
     echo '<li>';
   }
   echo '<a href="../about/">About</a>
   </li>';
   /*if(basename($_SERVER['PHP_SELF']) == "flux_rss/index.php")  {
     echo '<li class="active">';
   } else {
     echo '<li>';
   }
   echo '<a href="../flux_rss/">RSS</a>
   </li>';*/
   if($bool_verification_role) {
      echo '<li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin
            <span class="caret"></span></a>
            <ul class="dropdown-menu">';
            if(basename($_SERVER['PHP_SELF']) == "upload_morceau/index.php")  {
              echo '<li class="active">';
            } else {
              echo '<li>';
            }
            echo '<a href="../upload_morceau/">Admin : Upload d\'un morceau</a>
           </li>';

           if(basename($_SERVER['PHP_SELF']) == "ajout_artistes/index.php")  {
             echo '<li class="active">';
           } else {
             echo '<li>';
           }
           echo '<a href="../ajout_artistes/">Admin : Ajout artistes</a>
           </li>';

           if(basename($_SERVER['PHP_SELF']) == "selectionner_artiste_a_modifier/index.php")  {
             echo '<li class="active">';
           } else {
             echo '<li>';
           }
           echo '<a href="../selectionner_artiste_a_modifier/">Admin : Modifier artistes</a>
           </li>';

           if(basename($_SERVER['PHP_SELF']) == "add_artiste_to_morceau1/index.php")  {
             echo '<li class="active">';
           } else {
             echo '<li>';
           }
           echo '<a href="../add_artiste_to_morceau1/">Admin : Ajout des artistes au morceau</a>
           </li>';

           if(basename($_SERVER['PHP_SELF']) == "add_genre_to_morceau1/index.php")  {
             echo '<li class="active">';
           } else {
             echo '<li>';
           }
           echo '<a href="../add_genre_to_morceau1/">Admin : Ajout des genres au morceau</a>
           </li>';

           if(basename($_SERVER['PHP_SELF']) == "delete_morceau/index.php")  {
             echo '<li class="active">';
           } else {
             echo '<li>';
           }
           echo '<a href="../delete_morceau/">Admin : Suppression d\'un morceau</a>
           </li>';
   }
    echo '</ul>
    </li>';

   echo '</ul>

    <form class="navbar-form navbar-left" action="../search/" method="post">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Rechercher" name="search" />
      </div>
      <button type="submit" class="btn btn-default">Rechercher</button>
    </form>

   <ul class="nav navbar-nav navbar-right">';

   if($_SESSION["the_username"]) {
        if(basename($_SERVER['PHP_SELF']) == "profil/index.php")  {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        echo '<a href="../profil/"><span class="glyphicon glyphicon-user"></span> Profil '.$_SESSION["the_username"].'</a></li>';
        if(basename($_SERVER['PHP_SELF']) == "logout/index.php")  {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        echo '<a href="../logout/"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>';
   } else {
       if(basename($_SERVER['PHP_SELF']) == "register/index.php")  {
         echo '<li class="active">';
       } else {
         echo '<li>';
       }
       echo '<a href="../register/"><span class="glyphicon glyphicon-user"></span> Enregistrement</a></li>';
      if(basename($_SERVER['PHP_SELF']) == "login/index.php")  {
         echo '<li class="active">';
      } else {
         echo '<li>';
      }
      echo '<a href="../login/"><span class="glyphicon glyphicon-log-in"></span> Se connecter</a></li>';
   }
     echo '
     <li></li>
   </ul>
 </div>
</nav>';

}

?>
