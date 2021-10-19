<?php

include("../connection_bdd.php");
include("../logo_search_menu/index.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

if($_SESSION["the_role"] == "administrateur") {
    // Récupère la musique à écouter
    $musique = array();
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $stmt = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
    $stmt->execute(array($_GET["id"]));
    while ($row = $stmt->fetch()) {
        $musique["file_name"] = $row["file_name"];
        $musique["extension"] = $row["extension"];
        $musique["titre"] = $row["titre"];
    }

    echo "<div class='row'>
            <div class='col-lg-4'></div>
            <div class='col-lg-4'>";

    echo "<h3>".$musique["titre"]."</h3>";
    echo "<audio controls='controls'>
      <source src='../upload_musiques/".$musique['file_name'].$musique['extension']."' type='audio/ogg' />
      Votre navigateur n\'est pas compatible
    </audio><br /><br />";

    if(isset($_POST["delete_commentaire"])) {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        $stmt = $pdo->prepare("DELETE FROM commentaire WHERE id = ?");
        $stmt->execute(array($_POST["delete_commentaire"]));
    }
    // Va chercher les commentaires
    $commentaire = array();
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $stmt = $pdo->prepare("SELECT * FROM commentaire WHERE id_morceau = ?");
    $stmt->execute(array($_GET["id"]));
    $i = 0;
    while ($row = $stmt->fetch()) {
        $commentaire[$i] = array();
        $commentaire[$i]["id"] = $row["id"];
        $commentaire[$i]["texte"] = $row["texte"];
        $commentaire[$i]["date_"] = $row["date_"];

        // Va chercher l'auteur du commentaire
        $stmt_ = $pdo->prepare("SELECT * FROM user WHERE id = ?");
        $stmt_->execute(array($row["id_user"]));
        while ($ligne = $stmt_->fetch()) {
            $commentaire[$i]["auteur"] = $ligne["username"];
        }
        $i++;
    }

    if(sizeof($commentaire) > 0) {
        echo "<form action='./?id=" . $_GET["id"] . "' method='post'>";
        // Affichage des commentaires :
        for ($i=0; $i < sizeof($commentaire) ; $i++) {
            echo "<input name='delete_commentaire' type='radio' value='".$commentaire[$i]["id"]."' /> " . $commentaire[$i]["date_"] . " - <strong>" . $commentaire[$i]["auteur"] 
                 . "</strong> : " . $commentaire[$i]["texte"] . "<br />";
        }
        echo "<input type='submit' value='Supprimer' />";
        echo "</form>";
    }

    echo "<br /><br />
    <form action='../player/?id=".$_GET['id']."' method='post'>
        <label for='commentaire'>Commentaire : </label><br />
        <textarea id='commentaire' name='commentaire' placeholder='Exprimez-vous ici'></textarea>
        <input type='submit' value='Envoyer' />
        </form>";

        echo "<a href='../player/?id=".$_GET['id']."'>Revenir au lecteur</a>";

    echo '</div></div>';

}

echo '</body></html>';

?>
