<?php

include("connection_bdd.php");
include("logo_search_menu2.php");


function formulaire_upload($db_host_, $db_name_, $db_user_, $db_password_) {
    echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
          <div class='container'>
              <form method='post' action='upload.php' enctype='multipart/form-data'>
                  <label for='titre'>Titre : </label><input id='titre' name='titre' type='text' required /><br />
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
                  <label for='artiste'>Artiste :</label>
                  <select name='artiste'>";
                  try {
                      $pdo = new PDO("mysql:host=$db_host_;dbname=$db_name_", $db_user_, $db_password_);
                      $stmt = $pdo->query("SELECT * FROM artiste");
                      while ($row = $stmt->fetch()) {
                          echo "<option value='".$row['pseudonyme']."'>".$row['pseudonyme']."</option>";
                      }
                  } catch(PDOException $e) {
                      echo $sql . "<br>" . $e->getMessage();
                  }
                  echo "</select><br />
                  <input type='file' name='the_music' /> <br />
                  <input type='submit' name='envoyer' value='Envoyer' />
              </form>
          </div>
          </div>
        </div>";
}

if(isset($_POST['titre']) && isset($_POST['genre']) && isset($_POST['artiste']) && isset($_FILES['the_music']['name'])) {
  $dossier = "upload_musiques/";
  $fichier = basename($_FILES['the_music']['name']);
  $taille_maxi = 100000000; // 100 Mo
  $taille = filesize($_FILES['the_music']['tmp_name']); // Le fichier temporaire

  $extensions = array( '.ogg', '.wav', '.mp3', '.webm');

  $extension = strrchr($_FILES['the_music']['name'], '.');

  $file_name = strstr($_FILES['the_music']['name'], '.', true);
  //Début des vérifications de sécurité...
  if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
  {
       $erreur = 'Vous devez uploader un fichier de type .wav, .mp3 ou .webm';
  }
  if($taille > $taille_maxi) {
       $erreur = 'Le fichier est trop gros...';
  }
  if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
  {
       //On formate le nom du fichier ici...
       $fichier = strtr($fichier,
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
       $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
       if(move_uploaded_file($_FILES['the_music']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
       {
          // Affichage du logo , du formulaire de recherche et du menu
          print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
          $titre = $_POST['titre'];

          try {
             $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
             // set the PDO error mode to exception
             $stmt = $pdo->prepare("INSERT INTO morceau (titre, file_name, extension)  VALUES (:titre, :file_name, :extension)");
             $stmt->bindParam(':titre', $titre);
             $stmt->bindParam(':file_name', $file_name);
             $stmt->bindParam(':extension', $extension);
             //$stmt->bindParam(':duree', 0);
             //$stmt->bindParam(':date_de_parution', "");
             $stmt->execute();
             $id_morceau = $pdo->lastInsertId();

             // Va chercher l'id du genre à ajouter
             $id_du_genre = 0;
             $stmt = $pdo->prepare("SELECT * FROM genre where nom = ?");
             if ($stmt->execute(array($_POST['genre']))) {
                  while ($row = $stmt->fetch()) {
                      $id_du_genre = $row['id'];
                  }
            }

            // Va chercher l'id de l'artiste à ajouter
            $id_user = 0;
            $stmt = $pdo->prepare("SELECT * FROM user where username = ?");
            if ($stmt->execute(array($_POST['artiste']))) {
                 while ($row = $stmt->fetch()) {
                     $id_user = $row['id'];
                 }
           }

            $stmt = $pdo->prepare("INSERT INTO morceau_genre (id_morceau, id_genre)  VALUES (:id_morceau, :id_genre)");
            $stmt->bindParam(':id_morceau', $id_morceau);
            $stmt->bindParam(':id_genre', $id_du_genre);
            $stmt->execute();

            $stmt = $pdo->prepare("INSERT INTO artiste_morceau (id_user, id_morceau)  VALUES (:id_user, :id_morceau)");
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_morceau', $id_morceau);
            $stmt->execute();

             echo "<div class='row'>
                 <div class='col-lg-4'></div>
                 <div class='col-lg-4'>
                   <div class='container'>Enregistrement effectué avec succès !<br />
                   </div>
                </div>
                </div>";
          }
          catch(PDOException $e) {
              echo $sql . "<br>" . $e->getMessage();
          }

          echo "<div class='row'>
                    <div class='col-lg-4'></div>
                    <div class='col-lg-4'>
                        <div class='container'>Upload de la musique effectué avec succès !
                        </div>
                    </div>
              </div>
              </body>
              </html>";
       }
       else //Sinon (la fonction renvoie FALSE).
       {
            print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
            echo 'Echec de l\'upload !';
            formulaire_upload($db_host, $db_name, $db_user, $db_password);
            echo   '</body>
              </html>';
       }
  } else {
       print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
       echo $erreur;
       echo   '</body>
         </html>';
  }
} else {
    print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
    formulaire_upload($db_host, $db_name, $db_user, $db_password);
}

 ?>
