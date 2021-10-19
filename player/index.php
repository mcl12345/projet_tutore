<?php

include("../connection_bdd.php");
include("../logo_search_menu/index.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

// Récupère la musique à écouter
$musiquePresente = false;
$musique = array();
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
$stmt->execute(array($_GET["id"]));
while ($row = $stmt->fetch()) {
    $musiquePresente = true;
    $musique["file_name"]       = $row["file_name"];
    $musique["extension"]       = $row["extension"];
    $musique["titre"]           = $row["titre"];
    $musique["imageURL"]        = $row["imageURL"];
    $musique["description"]     = $row["description"];
}

if(!$musiquePresente) {
    echo "<div class='row'>
                <div class='col-lg-4'></div>
                <div class='col-lg-4'>Aucune musique disponible !</div>
            </div>
            </body>
            </html>";
        exit;
}

// Récupère les artistes de la musique
$artistes = array();
$statement = $pdo->prepare("SELECT * FROM artiste_morceau WHERE id_morceau = ?");
$statement->execute(array($_GET["id"]));
$i = 0;
while ($row = $statement->fetch()) {
    $statement_ = $pdo->prepare("SELECT * FROM artiste WHERE id = ?");
    $statement_->execute(array($row["id_artiste"]));
    while ($ligne = $statement_->fetch()) {
        $artistes[$i] = $ligne["pseudonyme"];
    }
    $i++;
}

// Récupère les genres musicaux de la musique
$genres = array();
$statement = $pdo->prepare("SELECT * FROM morceau_genre WHERE id_morceau = ?");
$statement->execute(array($_GET["id"]));
$i = 0;
while ($row = $statement->fetch()) {
    $statement_ = $pdo->prepare("SELECT * FROM genre WHERE id = ?");
    $statement_->execute(array($row["id_genre"]));
    while ($ligne = $statement_->fetch()) {
        $genres[$i] = $ligne["nom"];
    }
    $i++;
}

// --------------------------------------------------
// Gestion de l'historique
// --------------------------------------------------
$idUser = $_SESSION["the_id"];
$idMusique = $_GET["id"];
$date_ = date("Y-m-d h:m:s");

// Ajoute une ligne à l'historique en BDD
$stmt_ = $pdo->prepare("INSERT INTO historique (id_user, id_morceau, date_)  VALUES ( :id_user, :id_morceau, :date_)");
$stmt_->bindParam(':id_user', $idUser);
$stmt_->bindParam(':id_morceau', $idMusique);
$stmt_->bindParam(':date_', $date_);
$stmt_->execute();

// -------------------------------
// Affichage
// -------------------------------
echo "<div class='row'>
		<div class='col-md-4 col-md-offset-4'>";

echo "<h3>".$musique["titre"]."</h3><br />";
if($musique["imageURL"] != null && $musique["extension"] == ".ogg") {
    echo '<img src="../upload_images/'.$musique["imageURL"].'" width="270px" height="170px" /><br />
            <audio controls="controls">
            <source src="../upload_musiques/'.$musique["file_name"].$musique["extension"].'" type="audio/ogg" />
            Votre navigateur n\'est pas compatible
            </audio>';
}
if($musique["extension"] == ".webm") {
    echo '<video id="myVideo" width="400" height="222" controls="controls">
        <source src="../upload_musiques/'.$musique["file_name"].$musique["extension"].'" type="video/webm" />
        Ici l\'alternative à la vidéo : upload_musiques/'.$musique["file_name"].$musique["extension"].'"
    </video>';
}
echo "<br /><br /><strong>Artistes : </strong>";
for($i=0; $i<sizeof($artistes); $i++) {
    echo $artistes[$i] . " ";
}

echo "<br /><br />
      <strong>Genres : </strong>";

for($i=0; $i<sizeof($genres); $i++) {
    echo $genres[$i] . " ";
}
echo "<br /><br />
      <strong>Description : </strong>".$musique["description"] . "<br /><br />";

// JS - Make the background color darker.

echo    "<script>
            var video = document.getElementById('myVideo');
            video.onplaying = function() {
                document.body.style.backgroundColor = 'black';
            };
            video.onpause = function() {
                document.body.style.backgroundColor = 'white';
            };
        </script>";

// ----------------------------------
// Affichage du j'aime
// ----------------------------------
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$statement = $pdo->prepare("SELECT * FROM aimer WHERE id_morceau = ? AND id_user= ?");
$statement->execute(array($_GET["id"], $_SESSION["the_id"]));
$row = $statement->fetch();
echo "<div>";
if ($row != null) {
    echo "<div id='like'>
    <button type='button' onclick='jaimePas(likeFunction)'>J'aime déjà</button>
    </div>";
}
else {
    echo "<div id='like'>
    <button type='button' onclick='jaime(likeFunction)'>J'aime</button>
    </div>";
}

echo "</div>
<br /><br />";

// -------------------------------------
// Gestion des commentaires :
// -------------------------------------
$commentaire = array();
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$statement = $pdo->prepare("SELECT * FROM commentaire WHERE id_morceau = ?");
$statement->execute(array($_GET["id"]));
$i = 0;
while ($row = $statement->fetch()) {
    $commentaire[$i] = array();
    $commentaire[$i]["texte"] = $row["texte"];
    $commentaire[$i]["date_"] = $row["date_"];

    // Affichage de l'auteur du commentaire
    $statement_ = $pdo->prepare("SELECT * FROM user WHERE id = ?");
    $statement_->execute(array($row["id_user"]));
    while ($ligne = $statement_->fetch()) {
        $commentaire[$i]["auteur"] = $ligne["username"];
    }
    $i++;
}

for ($i=0; $i<sizeof($commentaire) ; $i++) {
    echo $commentaire[$i]["date_"] . " - <strong>" . $commentaire[$i]["auteur"] . "</strong> : " . $commentaire[$i]["texte"] . "<br />";
}


echo "<div id='nouveau_commentaire'></div>
        <br /><br />
    <form action='./?id=".$_GET['id']."' method='post'>
        <label for='commentaire'>Commentaire : </label>
        <br />
        <textarea id='commentaire' name='commentaire' placeholder='Exprimez-vous ici'></textarea>
        <button type='button' onclick='ajoutCommentaire(commentFunction); return false;'>Ajouter le commentaire</button>
    </form>";

if($_SESSION["the_role"] == "administrateur") {
    echo "<a href=../moderation/?id=".$idMusique.">Modérer</a>";
}

echo '</div>';

// Traitement en AJAX  - commentaire et j'aime
echo '<script>
      function ajoutCommentaire(commentFunction) {
        var mon_commentaire = document.getElementById("commentaire").value;

        var xhttp;
        if (window.XMLHttpRequest) {
            xhttp = new XMLHttpRequest();
        }
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                commentFunction(this);  // this = xhttp
            }
        };
        xhttp.open("GET", "../ajout-commentaire/?idMusique='.$_GET["id"].'&texte=" + mon_commentaire, true);
        xhttp.send();
      }
      function commentFunction(xhttp) {
        if(document.getElementById("nouveau_commentaire").innerHTML != "") {
            document.getElementById("nouveau_commentaire").innerHTML = document.getElementById("nouveau_commentaire").innerHTML + "<br />" + xhttp.responseText;
        } else {
            document.getElementById("nouveau_commentaire").innerHTML = xhttp.responseText;
        }
        document.getElementById("commentaire").value = "";
      }

</script>

<!-- Script JavaScript pour le bouton Jaime -->
<script>
    function jaime(likeFunction) {
      var xhttp;
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          likeFunction(this);   // this = xhttp
        }
      };
      xhttp.open("GET", "../ajout-j-aime/?idUser='.$_SESSION["the_id"].'&idMusique='.$_GET["id"].'", true);
      xhttp.send();
    }

    // ---------------------------------
    function jaimePas(likeFunction) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                likeFunction(this); // this = xhttp
            }
        };
        xhttp.open("GET", "../supprimer-j-aime/?idUser='.$_SESSION["the_id"].'&idMusique='.$_GET["id"].'", true);
        xhttp.send();
    }

    // ---------------------
    function likeFunction(xhttp) {
        document.getElementById("like").innerHTML = xhttp.responseText;
    }
</script>

</body></html>';

?>
