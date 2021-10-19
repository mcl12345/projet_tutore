<?php

include("../connection_bdd.php");
include("../logo_search_menu/index.php");

// Affichage du logo , de la barre de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

echo    "<div class='row'>
            <div class='col-lg-4'></div>
            <div class='col-lg-4'>";

if ( isset($_POST["recherche"]) ) {
    $search = $_POST["recherche"];
    $isInto = false;
    echo "Voici les musiques pour la recherche <strong>" . $search . "</strong> : <br /><br />";

    // Récupère une musique selon son titre
    $pdo = new PDO("mysql:host=$db_host; dbname=$db_name", $db_user, $db_password);
    $statement = $pdo->prepare("SELECT * FROM morceau WHERE titre LIKE '%$search%'");
    $statement->execute();
    while ($ligne = $statement->fetch()) {
        $isInto = true;
        echo "<a href='../player/?id=".$ligne["id"]."'>" . $ligne["titre"] . "</a>
                <br />";
    }

    // Récupère les musiques ayant pour artiste ce pseudonyme
    $statement = $pdo->prepare("SELECT * FROM artiste WHERE pseudonyme = ?");
    $statement->execute(array($search));
    while ($ligne = $statement->fetch()) {
        $_statement = $pdo->prepare("SELECT * FROM artiste_morceau WHERE id_artiste = ?");
        $_statement->execute(array($ligne["id"]));
        while ($_ligne = $_statement->fetch()) {
            $_statement_ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
            $_statement_->execute(array($_ligne["id_morceau"]));

            while ($_ligne_ = $_statement_->fetch()) {
                $isInto = true;
                echo "<a href='../player/?id=".$_ligne_["id"]."'>" . $_ligne_["titre"] . "</a>
                        <br />";
            }
        }
    }

    // Récupère les musiques d'un genre musical
    $statement = $pdo->prepare("SELECT * FROM genre WHERE nom = ?");
    $statement->execute(array($search));
    while ($ligne = $statement->fetch()) {
        $_statement = $pdo->prepare("SELECT * FROM morceau_genre WHERE id_genre = ?");
        $_statement->execute(array($ligne["id"]));
        while ($_ligne = $_statement->fetch()) {
            $_statement_ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
            $_statement_->execute(array($_ligne["id_morceau"]));
            while ($_ligne_ = $_statement_->fetch()) {
                $isInto = true;
                echo "<a href='../player/?id=".$_ligne_["id"]."'>" . $_ligne_["titre"] . "</a>
                        <br />";
            }
        }
    }

    if(!$isInto) {
        echo "Aucun résultats.";
    }
}

echo '</div></div>
    </body></html>';

?>
