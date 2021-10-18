<?php

session_start();

function print_LOGO_FORMSEARCH_MENU($db_host_, $db_name_, $db_user_, $db_password_) {

    $bool_verification_role = false;
    try {
        $pdo = new PDO("mysql:host=".$db_host_.";dbname=".$db_name_, $db_user_, $db_password_);
        $stmt = $pdo->prepare("SELECT * FROM user where username = ?");
        if($stmt->execute(array($_SESSION["the_username"]))) {
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
  <html>
  <head>
    <title>Plateforme musicale</title>
    <meta charset="UTF-8">
    <meta name="description" content="Plateforme musicale">
    <meta name="keywords" content="musique">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <link rel="icon" type="image/png" href="img/music_favicon.png">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link href="css/bootstrap5.css" rel="stylesheet">
    <script src="js/bootstrap5.js"></script>

    <link type="text/css" rel="stylesheet" href="css/index.css">

    <link type="text/css" rel="stylesheet" href="css/menu-mobile.css" />
    <link type="text/css" rel="stylesheet" href="css/mmenu-light-lib.css" />

    <script src="js/mmenu-light.js"></script>

    <link type="text/css" rel="stylesheet" href="css/progress-top-bar.css" />
    <script src="js/topbar.js"></script>
  </head>
  <body>
    <div class="topbar"></div>

        <div id="page" class="fixed-top">
			<div class="header">
                <span class="main_title">Plateforme musicale</span>

                <form class="navbar-form navbar-left decalage_navbar" action="search/" method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Recherche" id="recherche" name="recherche" style="z-index:0;" />
                        <div class="input-group-btn">
                            <button class="btn btn-light" type="submit" style="height:34px; margin-top:-15px; z-index:0;">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>
                </form>         

                <!-- Ref -->
                <a href="#menu"><span></span></a>
				
				<nav id="menu">
					<ul>
						<li class="Selected"><a href="http://213.32.90.43/projet-tutore">Accueil</a></li>';						
            if($_SESSION["the_username"]) {
              echo '<li><span>Listes</span>
                    <ul>
                      <li><a href="liste_likes/">Liste des musiques aimées</a></li>
                      <li><a href="liste_recente/">Liste de musique(s) écoutée(s) récemment</a></li>
                      <li><a href="liste_genre/">Liste par genre musical</a></li>
                    </ul>
                    </li>';
        }
           
        if($_SESSION["the_username"]) {      
            echo '<li><span>Administration</span>
			  	  <ul>
                    <li><a href="upload_musique_page/">Téléversement d\'une musique</a></li>
                    <li><a href="ajout_artistes/">Ajout d\'un artiste</a></li>
                    <li><a href="selectionner_artiste_a_modifier/"> Modification d\'un artiste</a></li>
                    <li><a href="add_artiste_to_morceau1/"> Ajout d\'un artiste à une musique</a></li>
                    <li><a href="add_genre_to_morceau1/"> Ajout d\'un genre musical à une musique</a></li>
                    <li><a href="delete_morceau/"> Suppression d\'une musique</a></li>
                </ul>
			  </li>';
        }

        if($_SESSION["the_username"]) {      
		    echo '<li><span>Paramètres</span>
            <ul>
              <li><a href="profil/"> Profil '.$_SESSION["the_username"].'</a></li>
              <li><a href="logout/"> Déconnexion</a></li>
          </ul>
          </li>';
        } else {
            echo '<li><span>Paramètres</span>
            <ul>
                <li><a href="register/"> Enregistrement</a></li>
                <li><a href="login/"> Se connecter</a></li>
            </ul>
        </li>';
        }
		echo '<li><a href="licence/">Licence</a></li>
        </ul>    
			</nav>
		</div><!-- page - fixed-top -->
	</div><!-- header -->
    
    <script>
        topbar.config({
            autoRun      : false, 
            barThickness : 5,
            barColors    : {
                "0"      : "rgba(26, 188, 156, .7)",
                ".3"     : "rgba(26, 188, 156, .7)",
                "1.0"    : "rgba(26, 188, 156, .7)"
            },
            shadowBlur   : 5,
            shadowColor  : "rgba(0, 0, 0, .5)",
            className    : "topbar",
        })
        topbar.show();
        (function step() {
            setTimeout(function() {  
            if (topbar.progress("+.01") < 1) { step(); } else { topbar.hide(); }
            }, 1);
        })()
    </script>

    <script>
        var menu = new MmenuLight(
        document.querySelector( "#menu" ),
        "all"
        );
    
        var navigator = menu.navigation({
        // selectedClass: "Selected",
        // slidingSubmenus: true,
        // theme: "dark",
        // title: "Menu"
        });
    
        var drawer = menu.offcanvas({
        // position: "left"
        });
    
        //	Open the menu.
        document.querySelector( "a[href=\"#menu\"]" )
        .addEventListener( "click", evnt => {
            evnt.preventDefault();
            drawer.open();
        });
    </script>
    <br /><br /><br />';
}

?>
