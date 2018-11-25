<?php

include("connection_bdd.php");
include("logo_search_menu2.php");


// Lance le script HTML d'affichage
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
            <div class='container'>";

echo "<h3>Recommandations</h3>";
// Va chercher les likes
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM aimer WHERE id_user = ?");
$stmt->execute(array($_COOKIE["the_id"]));
while ($row = $stmt->fetch()) {
    $morceau_id = $row['id_morceau'];

    // Récupère le morceau sur lequel on va rechercher le genre et puis afficher tous les morceaux de ce genre
    $stmt_ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
    $stmt_->execute(array($morceau_id));
    while ($ligne = $stmt_->fetch()) {
        // On récupère le genre
        $_stmt_ = $pdo->prepare("SELECT * FROM morceau_genre WHERE id_morceau = ?");
        $_stmt_->execute(array($ligne['id']));
        while ($_ligne = $_stmt_->fetch()) {
            // On récupère les id de morceaux de morceau_genre
            $__stmt_ = $pdo->prepare("SELECT * FROM morceau_genre WHERE id_genre = ?");
            $__stmt_->execute(array($_ligne["id_genre"]));
            while ($_ligne_ = $__stmt_->fetch()) {
                // On récupère les morceaux du genre
                $__stmt__ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
                $__stmt__->execute(array($_ligne_["id_morceau"]));
                while ($__ligne_ = $__stmt__->fetch()) {

                    if($__ligne_['extension'] == ".webm") { echo "video : " ;}
                    if($__ligne_['extension'] == ".ogg") { echo "audio : " ;}
                    echo "<a href='player.php?id=".$__ligne_["id"]."'>" . $__ligne_["titre"] . "</a><br />";
                }
            }
        }
    }
}

echo "</div></div></div>";


echo '</body>
</html>';

?>
