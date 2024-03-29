<?php

include("../connection_bdd.php");
include("../logo_search_menu/index.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

// Suppression d'un morceau
if( isset($_POST["id_morceau"]) ) {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);

    // Suppression des liens image et audio ou vidéo :
    $stmt = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
    $stmt->execute(array($_POST["id_morceau"]));
    while($row = $stmt->fetch()) {
        $chemin_musique = "../upload_musiques/" . $row["file_name"] . $row["extension"];
        $chemin_image = "../upload_images/" . $row["imageURL"];
        unlink($chemin_musique);

        if($chemin_image != null || $chemin_image != "") {
            unlink($chemin_image);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM aimer WHERE id_morceau = ? ");
    $stmt->execute(array($_POST["id_morceau"]));

    // Suppression de la musique
    $stmt = $pdo->prepare("DELETE FROM morceau WHERE id = ?");
    $stmt->execute(array($_POST["id_morceau"]));

    $stmt = $pdo->prepare("DELETE FROM commentaire WHERE id_morceau = ?");
    $stmt->execute(array($_POST["id_morceau"]));
}

echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>";

echo "<h3>Musique à supprimer : </h3><br />";

// Va chercher les morceaux
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM morceau");
$stmt->execute();
echo '<form action="./" method="post">';
while ($row = $stmt->fetch()) {
    echo "<input type='radio' id='id_morceau' name='id_morceau' value='".$row["id"]."' />
          &nbsp;&nbsp;&nbsp;";
    if($row["imageURL"] != null || $row["imageURL"] != "") {
        echo "<img width='50' height='50' src='../upload_images/".$row["imageURL"]."' />";
    }
    echo "&nbsp;&nbsp;&nbsp;
          <a href='../player/?id=".$row["id"]."'>" . $row["titre"] . "</a><br />";
}
echo "<br /><input value='Supprimer' type='submit' />";
echo "</form><br />";


echo "</div></div>";

echo '</body></html>';

?>
