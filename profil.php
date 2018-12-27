<?php

include("connection_bdd.php");
include("logo_search_menu2.php");


// -------------------------------------
function formulaire_HTML($db_host, $db_name, $db_user, $db_password) {
    echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
          <div class='container'>";

    echo "<form action='profil.php' method='post'>";
    echo "<label for='genre'>Choississez votre genre préféré :</label>
    <select name='genre_prefere'>";
    $id_artiste = 0;
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $stmt = $pdo->query("SELECT * FROM genre");
    while ($row = $stmt->fetch()) {
      $id = 0;
      $stmt_ = $pdo->prepare("SELECT * FROM profil");
      $stmt_->execute();
      while($ligne = $stmt_->fetch()) {
          if($ligne["genre_prefere"] == $row["id"]) {
              $id = $ligne["genre_prefere"];
              echo "<option value='".$row['id']."'selected>".$row['nom']."</option>";
          }
      }
      if($row["id"] != $id) {
          echo "<option value='".$row['id']."'>".$row['nom']."</option>";
      }
    }
    echo "<option value='999'>Aucun</option>";
    echo "</select>
    <input type='submit' value='Selectionner' /><br />";
    echo "</form>";
}

if( !empty($_POST["genre_prefere"])) {
    // Insertion MySQL ou MaJ
    try {
         $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
         $stmt = $pdo->prepare("SELECT * FROM profil");
         $stmt->execute();
         $is_created = false;
         while($row = $stmt->fetch()) {
            $is_created = true;
            // set the PDO error mode to exception
            $stmt = $pdo->prepare("UPDATE profil SET genre_prefere = ?");
            $stmt->execute(array($_POST["genre_prefere"]));
         }
         if(!$is_created){
            $stmt = $pdo->prepare("INSERT INTO profil ( genre_prefere ) VALUES (:genre_prefere) ");
            $stmt->bindParam(":genre_prefere", $_POST["genre_prefere"]);
            $stmt->execute();
         }

         print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

         echo "<div class='row'>
             <div class='col-lg-4'></div>
             <div class='col-lg-4'>
               <div class='container'>";
         echo "Changement effectué avec succès !<br />";
         echo "</div></div></div>";
         echo '</body>
              </html>';
    }
    catch(PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
    }
}
// On affiche le formulaire
else {
    // Affichage du logo , du formulaire de recherche et du menu
    print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
    formulaire_HTML($db_host, $db_name, $db_user, $db_password);
    echo '  </body>
      </html>';
}

 ?>
