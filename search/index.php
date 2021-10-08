<?php

include("../connection_bdd.php");
include("../logo_search_menu/index.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

echo "<div class='row'>
    <div class='col-lg-4'></div>
    <div class='col-lg-4'>
      <div class='container'>";
if ( isset($_POST["search"])) {
  $search = $_POST["search"];

  // Va chercher le titre d'un morceau
  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
  $stmt = $pdo->prepare("SELECT * FROM morceau WHERE titre LIKE '%$search%'");
  $stmt->execute();
  while ($ligne = $stmt->fetch()) {
      echo "<a href='../player/?id=".$ligne["id"]."'>" . $ligne["titre"] . "</a><br />";
  }

  // Va chercher les morceaux ayant pour artiste ce pseudonyme
  $is_into = false;
  $stmt = $pdo->prepare("SELECT * FROM artiste WHERE pseudonyme = ?");
  $stmt->execute(array($search));
  while ($ligne = $stmt->fetch()) {
      $_stmt = $pdo->prepare("SELECT * FROM artiste_morceau WHERE id_artiste = ?");
      $_stmt->execute(array($ligne["id"]));
      while ($_ligne = $_stmt->fetch()) {
          $_stmt_ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
          $_stmt_->execute(array($_ligne["id_morceau"]));

          while ($_ligne_ = $_stmt_->fetch()) {
              if(!$is_into) {
                  echo "Voici les morceaux pour la recherche <strong>" . $search . "</strong> : <br /><br />";
                  $is_into = true;
              }
            echo "<a href='../player/?id=".$_ligne_["id"]."'>" . $_ligne_["titre"] . "</a><br />";
          }
      }
  }

  // Va chercher les morceaux d'un genre
  $stmt = $pdo->prepare("SELECT * FROM genre WHERE nom = ?");
  $stmt->execute(array($search));
  while ($ligne = $stmt->fetch()) {
      $_stmt = $pdo->prepare("SELECT * FROM morceau_genre WHERE id_genre = ?");
      $_stmt->execute(array($ligne["id"]));
      while ($_ligne = $_stmt->fetch()) {
          $_stmt_ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
          $_stmt_->execute(array($_ligne["id_morceau"]));
          while ($_ligne_ = $_stmt_->fetch()) {
            echo "<a href='../player/?id=".$_ligne_["id"]."'>" . $_ligne_["titre"] . "</a><br />";
          }
      }
  }
}

echo '</div></div></div></body></html>';

?>
