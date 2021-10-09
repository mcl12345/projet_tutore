<?php

include("connection_bdd.php");
include("logo_search_menu.php");

print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

if(isset($_SESSION["the_username"])) {
  echo "<div class='row'>
          <div class='col-lg-4'></div>
          <div class='col-lg-4'>";
    echo "<h3>Recommandations</h3><br /><br />";
    echo "</div></div>";
} else {
    echo "";
    echo '<div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <!--<li data-target="#myCarousel" data-slide-to="2"></li>-->
      </ol>

      <!-- Wrapper for slides -->
      <div class="carousel-inner">
        <div class="item active">
          <img src="img/concert.jpg" alt="concert1" style="width:100%;">
        </div>

        <div class="item">
          <img src="img/concert2.jpg" alt="concert2" style="width:100%;">
        </div>

        <!--<div class="item">
          <img src="img/Basilique3.jpg" alt="Basilique3" style="width:100%;">
        </div>-->
      </div>

      <!-- Left and right controls -->
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>';
  echo "<div class='row'>
          <div class='col-lg-4'></div>
          <div class='col-lg-4'>";
    echo "<br /><br />Veuillez-vous connecter à <a href='login/'>Se connecter</a><br />ou vous inscrire, si vous êtes nouveau ici <a href='register/'>S'enregistrer</a>";
    echo "</div></div>";
}

$dejaListe = array();
$NB_COLONNES = 3;
$i = 0;
$j = 0;
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM profil WHERE id_user = ?");
$stmt->execute(array($_SESSION["the_id"]));

while ($row = $stmt->fetch()) {
    $stmt_ = $pdo->prepare("SELECT * FROM morceau_genre WHERE id_genre = ?");
    $stmt_->execute(array($row["genre_prefere"]));
    while ($row_ = $stmt_->fetch()) {
            // On vérifie que le morceau ne soit pas dupliqué :
            $is_into_list = false;
            for ($k=0; $k < sizeof($dejaListe); $k++) {
                if($dejaListe[$k] == $row_["id_morceau"]) {
                    $is_into_list = true;
                    break;
                }
            }
            if(!$is_into_list) {
                $dejaListe[] = $row_["id_morceau"];
                // On récupère les morceaux du genre
                $_stmt_ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
                $_stmt_->execute(array($row_["id_morceau"]));
                while ($_row_ = $_stmt_->fetch()) {
                  if($j == $i*$NB_COLONNES) {
                    echo "<div class='row'>";
                  }
                  echo "<div class='col-lg-4'>";
                  if($_row_['extension'] == ".webm") { echo "&nbsp;video : " ;}
                  if($_row_['extension'] == ".ogg") { echo "&nbsp;audio : " ;}
                  if($_row_["imageURL"] != null || $_row_["imageURL"] != "") {
                    echo "<a target='_blank' href='player/?id=".$_row_["id"]."'>
                                <img width='250' height='150' src='upload_images/".$_row_["imageURL"]."' loading='lazy' />
                        </a>";
                  }
                  // Si la taille du titre est supérieur à 30 caractère on coupe le titre et on met 3 points.
                  if(strlen($_row_["titre"]) > 30) {
                      echo "&nbsp;<strong><a target='_blank' href='player/?id=".$_row_["id"]."'>" . substr($_row_["titre"], 0, 30) . "...</a></strong><br />";
                  } else {
                      echo "&nbsp;<strong><a target='_blank' href='player/?id=".$_row_["id"]."'>" . substr($_row_["titre"], 0, 30) . "</a></strong><br />";
                  }
                  echo "</div><!-- fin class col lg 4 -->";
                  if($j == $i*$NB_COLONNES-1 && $j >= 2) {
                        $i++;
                        echo "</div><!-- fin row -->";
                  }
                  $j++;
            }
        }
    }
}

// Va chercher les likes
$j = 0;
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM aimer WHERE id_user = ?");
$stmt->execute(array($_SESSION["the_id"]));
while ($row = $stmt->fetch()) {
    $morceau_id = $row['id_morceau'];

    // Récupère le morceau sur lequel on va rechercher le genre et puis afficher tous les morceaux de ce genre
    $stmt_ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
    $stmt_->execute(array($morceau_id));
    while ($ligne = $stmt_->fetch()) {
        // On récupère le genre
        $_stmt_ = $pdo->prepare("SELECT * FROM morceau_genre WHERE id_morceau = ?");
        $_stmt_->execute(array($ligne['id']));
        while ($_ligne = $_stmt_->fetch()) {
            // On récupère les id de morceaux de morceau_genre
            $__stmt_ = $pdo->prepare("SELECT * FROM morceau_genre WHERE id_genre = ?");
            $__stmt_->execute(array($_ligne["id_genre"]));
            while ($_ligne_ = $__stmt_->fetch()) {

                // On vérifie que le morceau ne soit pas dupliqué :
                $is_into_list = false;
                for ($k=0; $k < sizeof($dejaListe); $k++) {
                    if($dejaListe[$k] == $_ligne_["id_morceau"]) {
                        $is_into_list = true;
                        break;
                    }
                }
                if(!$is_into_list) {
                    $dejaListe[] = $_ligne_["id_morceau"];
                    // On récupère les morceaux du genre
                    $__stmt__ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
                    $__stmt__->execute(array($_ligne_["id_morceau"]));
                    while ($__ligne_ = $__stmt__->fetch()) {
                        if($j == $i*$NB_COLONNES) {
                            echo "<div class='row'>";
                        }
                        echo "<div class='col-lg-4'>";
                        if($__ligne_['extension'] == ".webm") { echo "&nbsp;video : " ;}
                        if($__ligne_['extension'] == ".ogg") { echo "&nbsp;audio : " ;}
                        if($__ligne_["imageURL"] != null || $__ligne_["imageURL"] != "") {
                            echo "<a target='_blank' href='player/?id=".$__ligne_["id"]."'><img width='250' height='150' src='upload_images/".$__ligne_["imageURL"]."' /></a>";
                        }
                        // Si la taille du titre est supérieur à 30 caractère on coupe le titre et on met 3 points.
                        if(strlen($__ligne_["titre"]) > 30) {
                            echo "&nbsp;<strong><a target='_blank' href='player/?id=".$__ligne_["id"]."'>" . substr($__ligne_["titre"], 0, 30) . "...</a></strong><br />";
                        } else {
                            echo "&nbsp;<strong><a target='_blank' href='player/?id=".$__ligne_["id"]."'>" . substr($__ligne_["titre"], 0, 30) . "</a></strong><br />";
                        }
                        echo "</div><!-- fin class col lg 4 -->";
                        if($j == $i*$NB_COLONNES-1 && $j >= 2) {
                            $i++;
                            echo "</div><!-- fin row -->";
                        }
                        $j++;
                    }
                }
            }
        }
    }
}

if(sizeof($dejaListe) == 0) {
  echo "<div class='row'>
          <div class='col-lg-4'></div>
          <div class='col-lg-4'>";
    echo "Pour le moment, vous n'avez aucune recommandation par les 'morceaux aimés' ou le <a href='profil/'>'formulaire de genre préféré'</a>.";
    echo "</div></div>";
}

if(isset($_SESSION["the_username"])) {
    echo "</div></div></div>";
}


echo '</body>
</html>';

?>
