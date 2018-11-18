<?php

include("connection_bdd.php");
include("logo_search_menu2.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);


// Va chercher le morceau à écouter
$morceau = array();
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
$stmt->execute(array($_GET["id"]));
while ($row = $stmt->fetch()) {
    $morceau["file_name"] = $row["file_name"];
    $morceau["extension"] = $row["extension"];
    $morceau["titre"] = $row["titre"];
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
} else if(isset($_POST["aimer"])) {
    $stmt_ = $pdo->prepare("INSERT INTO aimer (id_morceau, id_user)  VALUES ( :id_morceau, :id_user)");
    $stmt_->bindParam(':id_morceau', $id_morceau);
    $stmt_->bindParam(':id_user', $id_user);
    $stmt_->execute();
} else if(isset($_POST["favoris"])) {
    $stmt_ = $pdo->prepare("INSERT INTO favoris (id_morceau, id_user)  VALUES ( :id_morceau, :id_user)");
    $stmt_->bindParam(':id_morceau', $id_morceau);
    $stmt_->bindParam(':id_user', $id_user);
    $stmt_->execute();
} else if(isset($_POST["supprimer_aimer"])) {
    $stmt_ = $pdo->prepare("DELETE FROM aimer WHERE id_morceau = ? AND id_user = ?");
    $stmt_->execute(array($id_morceau, $id_user));
} else if(isset($_POST["supprimer_favoris"])) {
    $stmt_ = $pdo->prepare("DELETE FROM favoris WHERE id_morceau = ? AND id_user = ?");
    $stmt_->execute(array($id_morceau, $id_user));
} else {
    // Ajoute une ligne à l'historique
    $stmt_ = $pdo->prepare("INSERT INTO historique (id_user, id_morceau)  VALUES ( :id_user, :id_morceau)");
    $stmt_->bindParam(':id_user', $id_user);
    $stmt_->bindParam(':id_morceau', $id_morceau);
    $stmt_->execute();
}

echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
            <div class='container'>";

echo "<h3>".$morceau["titre"]."</h3>";
if($morceau["extension"] == ".ogg") {
    echo '<audio controls="controls">
      <source src="upload_musiques/'.$morceau["file_name"].$morceau["extension"].'" type="audio/ogg" />
      Votre navigateur n\'est pas compatible
    </audio><br /><br />';
} else if($morceau["extension"] == ".webm") {
  echo '<video width="400" height="222" controls="controls">
    <source src="upload_musiques/'.$morceau["file_name"].$morceau["extension"].'" type="video/webm" />
    Ici l\'alternative à la vidéo : upload_musiques/'.$morceau["file_name"].$morceau["extension"].'"
  </video>';
}

// ----------------------------------
// Affichage du like et du favoris
// ----------------------------------
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM aimer WHERE id_morceau = ? AND id_user= ?");
$stmt->execute(array($_GET["id"], $_COOKIE["the_id"]));
$row = $stmt->fetch();
if ($row != null) {
  echo "<div class='row'>
  <form action='player.php?id=".$_GET["id"]."' method='post' style='display:inline'>
  <input type='hidden' name='supprimer_aimer' value='1' />
  <input value='Jaime déjà' type='submit'/>
  </form>";
}
else {
  echo "<div class='row'>
        <form action='player.php?id=".$_GET["id"]."' method='post' style='display:inline'>
        <input type='hidden' name='aimer' value='1' />
        <input value='Jaime' type='submit'/>
        </form>";
}
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM favoris WHERE id_morceau = ? AND id_user= ?");
$stmt->execute(array($_GET["id"], $_COOKIE["the_id"]));
$row = $stmt->fetch();
if ($row != null) {
  echo "<form action='player.php?id=".$_GET["id"]."' method='post' style='display:inline' >
  <input type='hidden' name='supprimer_favoris' value='1' />
  <input value='Mis en favoris' type='submit'/>
  </form><br /><br />";
} else {
    echo "<form action='player.php?id=".$_GET["id"]."' method='post' style='display:inline' >
    <input type='hidden' name='favoris' value='1' />
    <input value='Mettre en favoris' type='submit'/>
    </form><br /><br />";
}

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

echo "<br /><br />
<form action='player.php?id=".$_GET['id']."' method='post'>
    <label for='commentaire'>Commentaire : </label><br /><textarea id='commentaire' name='commentaire' placeholder='Exprimez-vous ici'></textarea>
    <input type='submit' value='Envoyer' />
    </form>";

if($_COOKIE["the_role"] == "administrateur") {
  echo "<a href=moderation.php?id=".$id_morceau.">Modérer</a>";
}

echo '</div></div></div></body></html>';

?>
