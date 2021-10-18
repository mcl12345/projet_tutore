<?php

include("../connection_bdd.php");
include("../logo_search_menu/index.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

function formulaire_upload($db_host_, $db_name_, $db_user_, $db_password_) {

    if($_SESSION["the_role"] == "administrateur") {

        echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
          <div class='container'>
              <form method='post' action='./?id_artiste=".$_GET["id_artiste"] . "'>";
              try {
                  $pdo = new PDO("mysql:host=$db_host_;dbname=$db_name_", $db_user_, $db_password_);
                  $stmt = $pdo->prepare("SELECT * FROM artiste WHERE id = ?");
                  $stmt->execute(array($_GET["id_artiste"]));
                  while ($row = $stmt->fetch()) {
                    echo "<label class='label_formulaire' for='nom'>Nom : </label>
                            <input id='nom' value='".$row["nom"]."' name='nom' type='text' required />
                            <br />
                            <label class='label_formulaire' for='prenom'>Prenom : </label>
                            <input id='prenom' value='".$row["prenom"]."' name='prenom' type='text' required />
                            <br />
                            <label class='label_formulaire' for='pseudonyme'>Pseudonyme : </label>
                            <input id='pseudonyme' value='".$row["pseudonyme"]."' name='pseudonyme' type='text' required />
                            <br />
                            <label class='label_formulaire' for='age'>Age :</label>
                            <input type='text' name='age' value='".$row["age"]."' id='age' required />
                            <br />
                            <label class='label_formulaire' for='genre'>Genre(s) : </label>";
                    
                    $stmt_ = $pdo->prepare("SELECT * FROM artiste_genre WHERE id_artiste = ?");
                    $stmt_->execute(array($row["id"]));
                    while ($ligne = $stmt_->fetch()) {
                        $_stmt_ = $pdo->prepare("SELECT * FROM genre WHERE id= ?");
                        $_stmt_->execute(array($ligne["id_genre"]));
                        while ($ligne_ = $_stmt_->fetch()) {
                          echo $ligne_['nom'] . " ";
                        }
                    }
                    echo "<br />
                    <select style='margin-left: 100px;' name='genre'>";

                        $stmt_ = $pdo->prepare("SELECT * FROM artiste_genre WHERE id_artiste = ?");
                        $stmt_->execute(array($row["id"]));
                        while ($ligne = $stmt_->fetch()) {
                            $_stmt_ = $pdo->prepare("SELECT * FROM genre WHERE id= ?");
                            $_stmt_->execute(array($ligne["id_genre"]));
                            while ($ligne_ = $_stmt_->fetch()) {
                              echo "<option value='".$ligne_['nom']."' selected'>".$ligne_['nom']."</option>";
                            }
                            $_stmt_ = $pdo->prepare("SELECT * FROM genre WHERE id <> ?");
                            $_stmt_->execute(array($ligne["id_genre"]));
                            while ($ligne_ = $_stmt_->fetch()) {
                              echo "<option value='".$ligne_['nom']."'>".$ligne_['nom']."</option>";
                            }
                        }
                        echo "</select>
                        <br />";

                      }

                    } catch(PDOException $e) {
                        echo $sql . "<br />" . $e->getMessage();
                    }

                  echo "<br /><br />
                        <input style='margin-left: 100px;' type='submit' name='envoyer' value='Envoyer' />
              </form>
          </div>
          </div>
        </div>";
    }
}

if(isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["pseudonyme"]) && isset($_POST["age"]) && isset($_POST["genre"])) {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    // set the PDO error mode to exception
    $sql = "UPDATE artiste SET nom = '".$_POST["nom"]."' , prenom = '".$_POST["prenom"]."', pseudonyme = '".$_POST["pseudonyme"]."', age='".$_POST["age"] . "' WHERE id = ".$_GET["id_artiste"];
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Va chercher l'id du genre à ajouter
    $id_du_genre = 0;
    $stmt = $pdo->prepare("SELECT * FROM genre where nom = ?");
    if ($stmt->execute(array($_POST['genre']))) {
         while ($row = $stmt->fetch()) {
             $id_du_genre = $row['id'];
         }
   }

   // Insère dans la table artiste_genre, le genre de l'artiste
   $stmt = $pdo->prepare("INSERT INTO artiste_genre (id_artiste, id_genre)  VALUES (:id_artiste, :id_genre)");
   $stmt->bindParam(':id_artiste', $_GET["id_artiste"]);
   $stmt->bindParam(':id_genre', $id_du_genre);
   $stmt->execute();

   echo "<div class='row'>
            <div class='col-lg-4'></div>
            <div class='col-lg-4'>
                <div class='container'>L'artiste " . $_POST["pseudonyme"] . " a été modifié avec succès !</div>
            </div>
        </div>";

} else {
    formulaire_upload($db_host, $db_name, $db_user, $db_password);
}

echo '</body></html>';

?>
