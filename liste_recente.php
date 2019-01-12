<?php

include("connection_bdd.php");
include("logo_search_menu2.php");

// Affichage du logo , du formulaire de recherche et du menu
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

if( isset($_POST["id_user"]) ) {
  $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
  $stmt = $pdo->prepare("DELETE FROM historique WHERE id_user = ?");
  $stmt->execute(array($_COOKIE["the_id"]));
}

echo "<div class='row'>
        <div class='col-lg-4'></div>
        <div class='col-lg-4'>
            <div class='container'>";

if(isset($_COOKIE["the_username"])) {
    echo "<h3>Musiques écoutées récemment</h3><br />";

    echo "<br /><form action='liste_recente.php' method='post'>
      <input type='hidden' name='id_user' value='".$_GET["the_id"]."' />
      <input type='submit' value='Nettoyer l historique' /></form><br />";

} else {
    echo "<br /><br />Veuillez-vous vous connecter à <a href='login.php'>Se connecter</a><br />ou vous inscrire si vous êtes nouveau ici <a href='register.php'>S'enregistrer</a>";
}

// Va chercher l'historique
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("SELECT * FROM historique WHERE id_user = ?");
$stmt->execute(array($_COOKIE["the_id"]));
while ($row = $stmt->fetch()) {
    $morceau_id = $row['id_morceau'];

    $stmt_ = $pdo->prepare("SELECT * FROM morceau WHERE id = ?");
    $stmt_->execute(array($morceau_id));
    while ($ligne = $stmt_->fetch()) {
        echo $row['date'] . " : <a href='player.php?id=".$morceau_id."'>" . $ligne["titre"] . "</a><br />";
    }
}

echo "</div></div></div>";

echo '</body></html>';

?>
