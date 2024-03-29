<?php

include("../connection_bdd.php");
include("../logo_search_menu/index.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

// Récupère le genre à afficher
$i = 0;
$genre = array();
$pdo = new PDO("mysql:host=$db_host; dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM genre");
$stmt->execute();
while($row = $stmt->fetch()) {
    // Pourquoi 999 précisement ?
    if($row['id'] != 999) {
        $genre[$i]["nom"] = $row['nom'];
        $genre[$i]["id"] = $row['id'];
        $i++;
    }
}

$morceau = array();
for($i=0; $i < sizeof($genre); $i++) {
    $morceau[$genre[$i]["nom"]] = array();
    try {
        $j = 0;
        $pdo = new PDO("mysql:host=$db_host; dbname=$db_name", $db_user, $db_password);
        $stmt = $pdo->prepare("SELECT * FROM morceau_genre WHERE id_genre = ?");
        if ($stmt->execute(array($genre[$i]["id"]))) {
          while ($row = $stmt->fetch()) {
              // Va chercher les morceaux de musique
              $stmt_morceau = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
              if ($stmt_morceau->execute(array($row["id_morceau"]))) {
                  while ($row_morceau = $stmt_morceau->fetch()) {
                      $morceau[$genre[$i]["nom"]][$j] = array();
                      $morceau[$genre[$i]["nom"]][$j]['identifiant'] = $row_morceau['id'];
                      $morceau[$genre[$i]["nom"]][$j]['titre'] = $row_morceau['titre'];
                      $morceau[$genre[$i]["nom"]][$j]['duree'] = $row_morceau['duree'];
                      $morceau[$genre[$i]["nom"]][$j]['date_de_parution'] = $row_morceau['date_de_parution'];
                      $morceau[$genre[$i]["nom"]][$j]['extension'] = $row_morceau['extension'];
                      $morceau[$genre[$i]["nom"]][$j]['file_name'] = $row_morceau['file_name'];
                      $j++;
                  }
              }
          }
        }
    } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
}

// ---------------------------------------------------------------------
// Partie Affichage
// ---------------------------------------------------------------------

$j = 0;
foreach ($morceau as $genre => $value) {
  if($j == 0 || $j == 3 || $j == 6) {
    echo "<div class='row' style='margin-left:25px;'>";
  }
      echo "<div class='col-lg-4'>";
          //echo "<div class='container'>";
              echo "<h3>" . $genre . " : </h3>";

    for ($i=0; $i < sizeof($value); $i++) {
        if($value[$i]['extension'] == ".webm") { echo "video : " ;}
        if($value[$i]['extension'] == ".ogg") { echo "audio : " ;}
        echo "<a href='../player/?id=".$value[$i]['identifiant']."'>" . $value[$i]['titre'] . "</a><br />";
        echo $value[$i]['duree'];
        echo $value[$i]['date_de_parution'];
    }
    echo "<br />";
    //echo "</div><!-- fin container -->";
    echo "</div><!-- fin class col lg 4 -->";
    if($j == 2 || $j == 5 || $j == 8) {
        echo "</div><!-- fin row -->";
    }

    $j++;
}

echo '</body></html>';

?>
