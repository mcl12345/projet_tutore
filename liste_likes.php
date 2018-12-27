<?php

include("connection_bdd.php");
include("logo_search_menu2.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
            <div class='container'>";


if(isset($_COOKIE["the_username"])) {
    echo "<h3>Les vidéos que j'aime</h3>";
} else {
    echo "<br /><br />Veuillez-vous vous connecter à <a href='login.php'>Se connecter</a><br />ou vous inscrire si vous êtes nouveau ici <a href='register.php'>S'enregistrer</a>";
}

$is_likes = false;
// Va chercher les musiques aimées :
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM aimer WHERE id_user = ?");
$stmt->execute(array($_COOKIE["the_id"]));
while ($row = $stmt->fetch()) {
    $is_likes = true;
    $morceau_id = $row['id_morceau'];

    $stmt_ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
    $stmt_->execute(array($morceau_id));
    while ($ligne = $stmt_->fetch()) {
        if($ligne["imageURL"] != null || $ligne["imageURL"] != "") {
            echo "<img width='250' height='150' src='upload_images/".$ligne["imageURL"]."' />&nbsp;";
        }
        if($ligne['extension'] == ".webm") { echo "video : " ;}
        if($ligne['extension'] == ".ogg") { echo "audio : " ;}
        echo "<strong><a href='player.php?id=".$morceau_id."'>" . $ligne["titre"] . "</a></strong><br />";
    }
}
if(!$is_likes = false) {
    echo "Vous n'avais aimer aucune musique pour le moment.";
}

echo "</div></div></div>";

echo '</body></html>';

?>
