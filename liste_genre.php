<?php

include("connection_bdd.php");
include("logo_search_menu2.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

// Va chercher le genre à afficher
$i = 0;
$genre = array();
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM genre");
$stmt->execute();
while ($row = $stmt->fetch()) {
    $genre[$i]["nom"] = $row['nom'];
    $genre[$i]["id"] = $row['id'];
    $i++;
}


$morceau = array();
for ($i=0; $i < sizeof($genre); $i++) {
    $morceau[$genre[$i]["nom"]] = array();
    try {
        $j = 0;
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
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
echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
            <div class='container'>";
foreach ($morceau as $genre => $value) {
    echo "<h3>" . $genre . " : </h3>";

    for ($i=0; $i < sizeof($value); $i++) {

        echo "<a href='player.php?id=".$value[$i]['identifiant']."'>" . $value[$i]['titre'] . "</a><br />";
        echo $value[$i]['extension']; echo "<br />";
        echo $value[$i]['duree']; echo "<br />";
        echo $value[$i]['date_de_parution']; echo "<br />";
    }
    echo "<br />";
}

echo '</div></div></div></body></html>';

?>
