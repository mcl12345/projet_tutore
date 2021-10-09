<?php

include("../connection_bdd.php");
include("../logo_search_menu/index.php");

function formulaire_HTML($db_host, $db_name, $db_user, $db_password) {
    echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>";

    echo "<form action='./' method='post'>";
    echo "<label for='genre'>Choississez votre genre préféré :</label>
    <select name='genre_prefere'>";
    $id_artiste = 0;
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $stmt = $pdo->query("SELECT * FROM genre");
    while ($row = $stmt->fetch()) {
      $id = 0;
      $stmt_ = $pdo->prepare("SELECT * FROM profil WHERE id_user = ?");
      $stmt_->execute(array($_COOKIE["the_id"]));
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
    echo "</select>
    <input type='submit' value='Selectionner' /><br />";
    echo "</form>";
}

if( !empty($_POST["genre_prefere"])) {
    // Insertion MySQL ou MaJ
    try {
         $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
         $stmt = $pdo->prepare("SELECT * FROM profil WHERE id_user = ?");
         $stmt->execute(array($_COOKIE["the_id"]));

         $is_created = false;
         while($row = $stmt->fetch()) {
            $is_created = true;
            echo $_POST["genre_prefere"];
            echo $_COOKIE["the_id"];
            // set the PDO error mode to exception
            $stmt = $pdo->prepare("UPDATE profil SET genre_prefere = ? WHERE id_user = ?");
            $stmt->execute(array($_POST["genre_prefere"], $_COOKIE["the_id"]));
         }
         if(!$is_created){
            $stmt = $pdo->prepare("INSERT INTO profil ( genre_prefere, id_user ) VALUES (:genre_prefere, :id_user)");
            $stmt->bindParam(":genre_prefere", $_POST["genre_prefere"]);
            $stmt->bindParam(":id_user", $_COOKIE["the_id"]);
            $stmt->execute();
         }

         print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

         echo "<div class='row'>
             <div class='col-lg-4'></div>
             <div class='col-lg-4'>";
         echo "Changement effectué avec succès !<br />";
         echo "</div></div>";
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
    if(isset($_COOKIE["the_id"])) {
      formulaire_HTML($db_host, $db_name, $db_user, $db_password);
    } else {
      echo "<div class='row'>
          <div class='col-lg-4'></div>
          <div class='col-lg-4'>";
      echo "Veuillez-vous connecter à <a href='../login/'>Se connecter</a><br />ou vous inscrire, si vous êtes nouveau ici <a href='../register/'>S'enregistrer</a>";
      echo "</div></div>";
    }
    echo '  </body>
      </html>';
}

 ?>
