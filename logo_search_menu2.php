<?php

function print_LOGO_FORMSEARCH_MENU($db_host_, $db_name_, $db_user_, $db_password_) {

  $bool_verification_role = false;
  try {
      $pdo = new PDO("mysql:host=$db_host_;dbname=$db_name_", $db_user_, $db_password_);
      $stmt = $pdo->prepare("SELECT * FROM user where username = ?");
      if ($stmt->execute(array($_COOKIE["the_username"]))) {
        while ($row = $stmt->fetch()) {
          if($row['role'] == "administrateur") {
              $bool_verification_role = true;
          }
        }
      }
  } catch(PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
  }

  echo '<html>
  <head>
  <title>Plateforme musicale</title>
  <meta charset="UTF-8">
    <meta name="description" content="Plateforme musicale">
    <meta name="keywords" content="musique">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
  <nav class="navbar navbar-inverse">
      <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Plateforme musicale</a>
          </div>
          <ul class="nav navbar-nav">';

   if(basename($_SERVER['PHP_SELF']) == "index.php")  {
     echo '<li class="active">';
   } else {
     echo '<li>';
   }
   echo '<a href="index.php">Accueil</a>
   </li>';
   echo '<li class="dropdown">
         <a class="dropdown-toggle" data-toggle="dropdown" href="#">Listes
         <span class="caret"></span></a>
         <ul class="dropdown-menu">';
             if(basename($_SERVER['PHP_SELF']) == "liste_likes.php")  {
               echo '<li class="active">';
             } else {
               echo '<li>';
             }
             echo '<a href="liste_likes.php">Liste des likés</a>
             </li>';
             if(basename($_SERVER['PHP_SELF']) == "liste_recente.php")  {
               echo '<li class="active">';
             } else {
               echo '<li>';
             }
             echo '<a href="liste_recente.php">Liste récente</a>
             </li>';
             if(basename($_SERVER['PHP_SELF']) == "liste_genre.php")  {
               echo '<li class="active">';
             } else {
               echo '<li>';
             }
             echo '<a href="liste_genre.php">Liste par genre</a>
             </li>';
   echo '</ul>
   </li>';
   if(basename($_SERVER['PHP_SELF']) == "about.php")  {
     echo '<li class="active">';
   } else {
     echo '<li>';
   }
   echo '<a href="about.php">About</a>
   </li>';
   if($bool_verification_role) {
      echo '<li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Admin
            <span class="caret"></span></a>
            <ul class="dropdown-menu">';
            if(basename($_SERVER['PHP_SELF']) == "upload.php")  {
              echo '<li class="active">';
            } else {
              echo '<li>';
            }
            echo '<a href="upload.php">Admin : Upload</a>
           </li>';

           if(basename($_SERVER['PHP_SELF']) == "ajout_artistes.php")  {
             echo '<li class="active">';
           } else {
             echo '<li>';
           }
           echo '<a href="ajout_artistes.php">Admin : Ajout artistes</a>
           </li>';

           if(basename($_SERVER['PHP_SELF']) == "add_artiste_to_morceau1.php")  {
             echo '<li class="active">';
           } else {
             echo '<li>';
           }
           echo '<a href="add_artiste_to_morceau1.php">Admin : Ajout des artistes au morceau</a>
           </li>';

           if(basename($_SERVER['PHP_SELF']) == "add_genre_to_morceau1.php")  {
             echo '<li class="active">';
           } else {
             echo '<li>';
           }
           echo '<a href="add_genre_to_morceau1.php">Admin : Ajout des genres au morceau</a>
           </li>';
   }
    echo '</ul>
    </li>';

   echo '</ul>

    <form class="navbar-form navbar-left" action="search.php" method="post">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Rechercher" name="search" />
      </div>
      <button type="submit" class="btn btn-default">Rechercher</button>
    </form>

   <ul class="nav navbar-nav navbar-right">';

   if($_COOKIE["the_username"]) {
     if(basename($_SERVER['PHP_SELF']) == "logout.php")  {
       echo '<li class="active">';
     } else {
       echo '<li>';
     }
     echo '<a href="logout.php">Log out</a>
             </li>';
   } else {
       if(basename($_SERVER['PHP_SELF']) == "register.php")  {
         echo '<li class="active">';
       } else {
         echo '<li>';
       }
       echo '<a href="register.php"><span class="glyphicon glyphicon-user"></span> Enregistrement</a>
             </li>';
      if(basename($_SERVER['PHP_SELF']) == "login.php")  {
         echo '<li class="active">';
      } else {
         echo '<li>';
      }
      echo '<a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Se connecter</a>
             </li>';
   }
     echo '
     <li></li>
   </ul>
 </div>
</nav>';

}

?>
