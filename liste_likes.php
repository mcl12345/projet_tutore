<?php

include("connection_bdd.php");
include("logo_search_menu2.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
            <div class='container'>";

echo "<h3>Les vidéos que j'aime</h3>";
// Va chercher l'historique
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM aimer WHERE id_user = ?");
$stmt->execute(array($_COOKIE["the_id"]));
while ($row = $stmt->fetch()) {
    $morceau_id = $row['id_morceau'];

    $stmt_ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
    $stmt_->execute(array($morceau_id));
    while ($ligne = $stmt_->fetch()) {

        if($ligne['extension'] == ".webm") { echo "video : " ;}
        if($ligne['extension'] == ".ogg") { echo "audio : " ;}
        echo "<a href='player.php?id=".$morceau_id."'>" . $ligne["titre"] . "</a><br />";
    }
}

echo "</div></div></div>";

echo '</body></html>';

?>
