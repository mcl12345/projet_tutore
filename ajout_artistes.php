<?php

include("connection_bdd.php");
include("logo_search_menu2.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

function formulaire_upload($db_host_, $db_name_, $db_user_, $db_password_) {
    echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
          <div class='container'>
              <form method='post' action='ajout_artistes.php' enctype='multipart/form-data'>
                  <label for='nom'>Nom : </label><input id='nom' name='nom' type='text' required /><br />
                  <label for='prenom'>Prenom : </label><input id='prenom' name='prenom' type='text' required /><br />
                  <label for='pseudonyme'>Pseudonyme : </label><input id='pseudonyme' name='pseudonyme' type='text' required /><br />
                  <label for='genre'>Genre : </label>
                  <select name='genre'>";
                  try {
                      $pdo = new PDO("mysql:host=$db_host_;dbname=$db_name_", $db_user_, $db_password_);
                      $stmt = $pdo->query("SELECT * FROM genre");
                      while ($row = $stmt->fetch()) {
                          echo "<option value='".$row['nom']."'>".$row['nom']."</option>";
                      }
                  } catch(PDOException $e) {
                      echo $sql . "<br>" . $e->getMessage();
                  }

                  echo "</select><br />
                  <label for='age'>Age :</label><input type='text' name='age' id='age' required /> <br />
                  <input type='submit' name='envoyer' value='Envoyer' />
              </form>
          </div>
          </div>
        </div>";
}

if( isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["pseudonyme"]) && isset($_POST["age"]) && isset($_POST["genre"])) {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    // set the PDO error mode to exception
    $stmt = $pdo->prepare("INSERT INTO artiste (nom, prenom, pseudonyme, age)  VALUES (:nom, :prenom, :pseudonyme, :age)");
    $stmt->bindParam(':nom', $_POST["nom"]);
    $stmt->bindParam(':prenom', $_POST["prenom"]);
    $stmt->bindParam(':pseudonyme', $_POST["pseudonyme"]);
    $stmt->bindParam(':age', $_POST["age"]);
    $stmt->execute();
    $id_artiste = $pdo->lastInsertId();

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
   $stmt->bindParam(':id_artiste', $id_artiste);
   $stmt->bindParam(':id_genre', $id_du_genre);
   $stmt->execute();

   echo "<div class='row'>
       <div class='col-lg-4'></div>
       <div class='col-lg-4'>
         <div class='container'>L'artiste " . $_POST["pseudonyme"] . " a été enregistré avec succès !<br />
         </div>
      </div>
      </div>";

} else {
    formulaire_upload($db_host, $db_name, $db_user, $db_password);
}

echo '</body></html>';

?>
