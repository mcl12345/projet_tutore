<?php

include("connection_bdd.php");
include("logo_search_menu2.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);


// Va chercher le morceau à écouter
$morceau_present = false;
$morceau = array();
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
$stmt->execute(array($_GET["id"]));
while ($row = $stmt->fetch()) {
    $morceau_present = true;
    $morceau["file_name"] = $row["file_name"];
    $morceau["extension"] = $row["extension"];
    $morceau["titre"]     = $row["titre"];
    $morceau["imageURL"]  = $row["imageURL"];
    $morceau["description"]  = $row["description"];
}

if(!$morceau_present) {
  echo "<div class='row'>
          <div class='col-lg-4'></div>
          <div class='col-lg-4'>
              <div class='container'>";
    echo "Aucun morceau disponible !</div></div></div></body></html>";
    exit(0);
}

// Va chercher les artistes du morceau
$artistes = array();
$stmt = $pdo->prepare("SELECT * FROM artiste_morceau WHERE id_morceau = ?");
$stmt->execute(array($_GET["id"]));
$i = 0;
while ($row = $stmt->fetch()) {
    $stmt_ = $pdo->prepare("SELECT * FROM artiste WHERE id = ?");
    $stmt_->execute(array($row["id_artiste"]));
    while ($ligne = $stmt_->fetch()) {
        $artistes[$i] = $ligne["pseudonyme"];
    }
    $i++;
}

// Va chercher les genres du morceau
$genres = array();
$stmt = $pdo->prepare("SELECT * FROM morceau_genre WHERE id_morceau = ?");
$stmt->execute(array($_GET["id"]));
$i = 0;
while ($row = $stmt->fetch()) {
    $stmt_ = $pdo->prepare("SELECT * FROM genre WHERE id = ?");
    $stmt_->execute(array($row["id_genre"]));
    while ($ligne = $stmt_->fetch()) {
        $genres[$i] = $ligne["nom"];
    }
    $i++;
}

// --------------------------------------------------
// Gestion de l'enregistrement des commentaires
// --------------------------------------------------
$id_user = $_COOKIE["the_id"];
$id_morceau = $_GET["id"];

if(isset($_POST["commentaire"])) {
    $texte = $_POST["commentaire"];
    $stmt_ = $pdo->prepare("INSERT INTO commentaire (id_morceau, id_user, texte)  VALUES ( :id_morceau, :id_user, :texte)");
    $stmt_->bindParam(':id_morceau', $id_morceau);
    $stmt_->bindParam(':id_user', $id_user);
    $stmt_->bindParam(':texte', $texte);
    $stmt_->execute();
} else {
    // Ajoute une ligne à l'historique
    $stmt_ = $pdo->prepare("INSERT INTO historique (id_user, id_morceau)  VALUES ( :id_user, :id_morceau)");
    $stmt_->bindParam(':id_user', $id_user);
    $stmt_->bindParam(':id_morceau', $id_morceau);
    $stmt_->execute();
}

// -------------------------------
// Affichage
// -------------------------------
/*echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
            <div class='container'>";*/
echo "<div class='container'>
			<div class='row'>
				<div class='col-md-4 col-md-offset-4'>";

echo "<h3>".$morceau["titre"]."</h3><br />";
if($morceau["imageURL"] != null && $morceau["extension"] == ".ogg") {
  echo "<img src='upload_images/".$morceau["imageURL"]."' width='270px' height='170px' /><br />";
}
if($morceau["extension"] == ".ogg") {
    echo '<audio controls="controls">
      <source src="upload_musiques/'.$morceau["file_name"].$morceau["extension"].'" type="audio/ogg" />
      Votre navigateur n\'est pas compatible
    </audio>';
} else if($morceau["extension"] == ".webm") {
  echo '<video width="400" height="222" controls="controls">
    <source src="upload_musiques/'.$morceau["file_name"].$morceau["extension"].'" type="video/webm" />
    Ici l\'alternative à la vidéo : upload_musiques/'.$morceau["file_name"].$morceau["extension"].'"
  </video>';
}
echo "<br /><br /><strong>Artistes : </strong>";
for($i=0; $i<sizeof($artistes); $i++) {
    echo $artistes[$i] . " ";
}
echo "<br /><br />";
echo "<strong>Genres : </strong>";
for($i=0; $i<sizeof($genres); $i++) {
    echo $genres[$i] . " ";
}
echo "<br /><br />";
echo "<strong>Description : </strong>".$morceau["description"] . "<br /><br />";

// ----------------------------------
// Affichage du like et du favoris
// ----------------------------------
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM aimer WHERE id_morceau = ? AND id_user= ?");
$stmt->execute(array($_GET["id"], $_COOKIE["the_id"]));
$row = $stmt->fetch();
echo "<div>";
if ($row != null) {
  /*echo "<form action='player.php?id=".$_GET["id"]."' method='post' style='display:inline'>
  <input type='hidden' name='supprimer_aimer' value='1' />
  <input value='Jaime déjà' onclick='jaime(myFunction)' type='submit'/>
  </form>";*/
  echo '<div id="demo">
  <button type="button" onclick="jaimePas(myFunction)">Jaime déjà</button>
  </div>';
}
else {
  /*echo "
        <form action='player.php?id=".$_GET["id"]."' method='post' style='display:inline'>
        <input type='hidden' name='aimer' value='1' />
        <input value='Jaime' onclick='jaime(myFunction)' type='submit'/>
        </form>";*/
        echo '<div id="demo">
        <button type="button" onclick="jaime(myFunction)">Jaime</button>
        </div>';
}

echo "</div><br /><br />";

// -------------------------------------
// Affichage des commentaires :
// -------------------------------------
// Va chercher les commentaires
$commentaire = array();
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM commentaire WHERE id_morceau = ?");
$stmt->execute(array($_GET["id"]));
$i = 0;
while ($row = $stmt->fetch()) {
    $commentaire[$i] = array();
    $commentaire[$i]["texte"] = $row["texte"];
    $commentaire[$i]["date"] = $row["date"];

    // Va chercher l'auteur du commentaire
    $stmt_ = $pdo->prepare("SELECT * FROM user WHERE id = ?");
    $stmt_->execute(array($row["id_user"]));
    while ($ligne = $stmt_->fetch()) {
        $commentaire[$i]["auteur"] = $ligne["username"];
    }
    $i++;
}

for ($i=0; $i < sizeof($commentaire) ; $i++) {
    echo $commentaire[$i]["date"] . " - <strong>" . $commentaire[$i]["auteur"] . "</strong> : " . $commentaire[$i]["texte"] . "<br />";
}

// Traitement en AJAX
echo '<div id="nouveau_commentaire"></div>';

echo "<br /><br />
<form action='player.php?id=".$_GET['id']."' method='post' >
    <label for='commentaire'>Commentaire : </label><br /><textarea id='commentaire' name='commentaire' placeholder='Exprimez-vous ici'></textarea>
<button type='button' onclick='add_commentaire(myFunction2); return false;'>Add commentaire</button>
    </form>";

if($_COOKIE["the_role"] == "administrateur") {
  echo "<a href=moderation.php?id=".$id_morceau.">Modérer</a>";
}

echo '</div></div></div>';

echo '<script>
      function add_commentaire(cFunction) {
        var mon_commentaire = document.getElementById("commentaire").value;

        var xhttp;
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xhttp = new XMLHttpRequest();
         } else {
            // code for old IE browsers
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            cFunction(this);
          }
        };
        xhttp.open("GET", "add_commentaire.php?id_morceau='.$_GET["id"].'&texte=" + mon_commentaire, true);
        xhttp.send();
      }
      function myFunction2(xhttp) {
        if(document.getElementById("nouveau_commentaire").innerHTML != "") {
          document.getElementById("nouveau_commentaire").innerHTML = document.getElementById("nouveau_commentaire").innerHTML + "<br />" + xhttp.responseText;
        } else {
          document.getElementById("nouveau_commentaire").innerHTML = xhttp.responseText;
        }
        document.getElementById("commentaire").value = "";
      }

</script>';

echo '<!-- Script JavaScript pour le bouton J\'aime -->
<script>
    function jaime(cFunction) {
      var xhttp;
      xhttp=new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          cFunction(this);
        }
      };
      xhttp.open("GET", "add_jaime.php?id_user='.$_COOKIE["the_id"].'&id_morceau='.$_GET["id"].'", true);
      xhttp.send();
    }
    function myFunction(xhttp) {
      document.getElementById("demo").innerHTML =
      xhttp.responseText;
    }

    // ---------------------------------
    // JAIME PAS
    function jaimePas(cFunction) {
      var xhttp;
      xhttp=new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          cFunction(this);
        }
      };
      xhttp.open("GET", "remove_jaime.php?id_user='.$_COOKIE["the_id"].'&id_morceau='.$_GET["id"].'", true);
      xhttp.send();
    }
    function myFunction(xhttp) {
      document.getElementById("demo").innerHTML =
      xhttp.responseText;
    }
</script>

</body></html>';

?>
