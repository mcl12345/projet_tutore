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
    <link rel="stylesheet" type="text/css" href="bootstrap-4.1.3/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.1.3/css/bootstrap-reboot.css">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.1.3/css/bootstrap-grid.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <script src="bootstrap-4.1.3/js/bootstrap.bundle.js"></script>
    <script src="bootstrap-4.1.3/js/bootstrap.js"></script>
  </head>
  <body>
  <br />
      <!-- LOGO + FORM_SEARCH -->
      <div class="row">
        <div class="col-lg-2">
        </div><!-- /.col-lg-2 -->
        <!-- LOGO -->
        <div class="col-lg-2">
          <div class="logo">
            <img src="img/chat_forestier.jpg" />
          </div>
        </div><!-- /.col-lg-2 -->
        <!-- search -->
        <div class="col-lg-4">
        <form class="formulaire" method="get" action="search.php">
          <div class="input-group">
                  <input type="text" class="form-control" placeholder="Rechercher...">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">Rechercher</button>
                  </span>
          </div><!-- /input-group -->
          </form>
        </div><!-- /.col-lg-4 -->
      </div><!-- /.row -->
      <!-- end LOGO + FORM_SEARCH -->

    <br /><br />

    <!-- MENU -->
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
          <div class="container">
          <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav">';
                if(basename($_SERVER['PHP_SELF']) == "index.php")  {
                  echo '<li class="nav-item active">';
                } else {
                  echo '<li class="nav-item">';
                }
                echo '<a class="nav-link" href="index.php">Accueil <span class="sr-only">(current)</span></a>
                </li>';
                if(basename($_SERVER['PHP_SELF']) == "liste_likes.php")  {
                  echo '<li class="nav-item active">';
                } else {
                  echo '<li class="nav-item">';
                }
                echo '<a class="nav-link" href="liste_likes.php">Liste des likés</a>
                </li>';
                if(basename($_SERVER['PHP_SELF']) == "liste_favoris.php")  {
                  echo '<li class="nav-item active">';
                } else {
                  echo '<li class="nav-item">';
                }
                echo '<a class="nav-link" href="liste_favoris.php">Liste des favoris</a>
                </li>';
                if(basename($_SERVER['PHP_SELF']) == "liste_recente.php")  {
                  echo '<li class="nav-item active">';
                } else {
                  echo '<li class="nav-item">';
                }
                echo '<a class="nav-link" href="liste_recente.php">Liste récente</a>
                </li>';
                if(basename($_SERVER['PHP_SELF']) == "liste_genre.php")  {
                  echo '<li class="nav-item active">';
                } else {
                  echo '<li class="nav-item">';
                }
                echo '<a class="nav-link" href="liste_genre.php">Liste par genre</a>
                </li>';
                if($_COOKIE["the_username"]) {
                  if(basename($_SERVER['PHP_SELF']) == "logout.php")  {
                    echo '<li class="nav-item active">';
                  } else {
                    echo '<li class="nav-item">';
                  }
                  echo '<a class="nav-link" href="logout.php">Log out</a>
                          </li>';
                } else {
                    if(basename($_SERVER['PHP_SELF']) == "register.php")  {
                      echo '<li class="nav-item active">';
                    } else {
                      echo '<li class="nav-item">';
                    }
                    echo '<a class="nav-link" href="register.php">Register</a>
                          </li>';
                          if(basename($_SERVER['PHP_SELF']) == "login.php")  {
                            echo '<li class="nav-item active">';
                          } else {
                            echo '<li class="nav-item">';
                          }
                          echo '<a class="nav-link" href="login.php">Login</a>
                          </li>';
                }
                if(basename($_SERVER['PHP_SELF']) == "about.php")  {
                  echo '<li class="nav-item active">';
                } else {
                  echo '<li class="nav-item">';
                }
                echo '<a class="nav-link" href="about.php">About</a>
                </li>
                ';
                if($bool_verification_role) {
                  if(basename($_SERVER['PHP_SELF']) == "upload.php")  {
                    echo '<li class="nav-item active">';
                  } else {
                    echo '<li class="nav-item">';
                  }
                  echo '<a class="nav-link" href="upload.php">Upload</a>
                          </li>';

                  if(basename($_SERVER['PHP_SELF']) == "ajout_artistes.php")  {
                    echo '<li class="nav-item active">';
                  } else {
                    echo '<li class="nav-item">';
                  }
                  echo '<a class="nav-link" href="ajout_artistes.php">Ajout artistes</a>
                      </li>';
                }

              echo '</ul>
            </div>
          </nav>
          </div><!-- end of container -->
      </div><!-- class="col-lg-6" -->
    </div><!-- end of the row -->
    <!-- end of MENU -->';
}

?>
