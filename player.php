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

$id_user = $_COOKIE["the_id"];
$id_morceau = $_GET["id"];
// Gestion de l'enregistrement des commentaires
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
    //$stmt->bindParam(':date_', date('d-m-Y H:i:s'));
    $stmt_->execute();
}

echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
            <div class='container'>";

echo "<h3>".$morceau["titre"]."</h3>";
echo '<audio controls="controls">
  <source src="upload_musiques/'.$morceau["file_name"].$morceau["extension"].'" type="audio/ogg" />
  Votre navigateur n\'est pas compatible
</audio><br /><br />';

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

// Affichage des commentaires :
for ($i=0; $i < sizeof($commentaire) ; $i++) {
    echo $commentaire[$i]["date"] . " - <strong>" . $commentaire[$i]["auteur"] . "</strong> : " . $commentaire[$i]["texte"] . "<br />";
}

echo "<br /><br />
<form action='player.php?id=".$_GET['id']."' method='post'>
    <label for='commentaire'>Commentaire : </label><br /><textarea id='commentaire' name='commentaire' placeholder='Exprimez-vous ici'></textarea>
    <input type='submit' value='Envoyer' />
    </form>";

echo "<a href=moderation.php?id=".$id_morceau.">Modérer</a>";
//"<form action='moderation.php?id='".$id_morceau."' method='post'><input type='submit' value='Modérer' /></form>";

echo '</div></div></div></body></html>';

?>
