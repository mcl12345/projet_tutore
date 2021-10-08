<?php

include("../connection_bdd.php");
include("../logo_search_menu/index.php");

print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

// -------------------------------------

echo "<div class='row'>
    <div class='col-lg-4'></div>
    <div class='col-lg-4'>
      <div class='container'>";

echo "<form action='../add_artiste_to_morceau2/' method='get'>";
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$confirmation = false;
$stmt = $pdo->prepare("SELECT * FROM morceau");
if ($stmt->execute()) {
     while ($row = $stmt->fetch()) {
        $confirmation = false;
        $stmt_ = $pdo->prepare("SELECT * FROM artiste_morceau WHERE id_morceau= ?");
        $stmt_->execute(array($row['id']));
          while ($ligne = $stmt_->fetch()) {
            $_stmt_ = $pdo->prepare("SELECT * FROM artiste WHERE id= ?");
            $_stmt_->execute(array($ligne['id_artiste']));
              while ($ligne_ = $_stmt_->fetch()) {
                echo $ligne_["pseudonyme"] . " : " ;
                echo "<label for='id_morceau'>".$row['titre']."</label> &nbsp&nbsp<input value='".$row['id']."' id='id_morceau' name='id_morceau' type='radio' /> <br />";
                $confirmation = true;
              }
          }
          if(!$confirmation) {
            echo "<label for='id_morceau'>".$row['titre']."</label> &nbsp&nbsp<input value='".$row['id']."' id='id_morceau' name='id_morceau' type='radio' /> <br />";
          }
     }
}
echo "<input value='Sélectionner' type='submit' />";
echo "</form>";


echo   '</div>
  </div>
</div>
</body>
  </html>';

?>
