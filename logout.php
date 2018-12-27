<?php

include("connection_bdd.php");
include("logo_search_menu2.php");

if (isset($_COOKIE["the_username"])) {
    unset($_COOKIE["the_username"]); // cela ne semble pas fonctionner
    unset($_COOKIE["the_email"]);
    unset($_COOKIE["the_role"]);
    unset($_COOKIE["the_id"]);
    setcookie("the_username", "", time()-3600); // Mettre une date antérieure le force à se supprimer au prochain chargement de page.
    setcookie("the_email", "", time()-3600);
    setcookie("the_role", "", time()-3600);
    setcookie("the_id", "", time()-3600);

    print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
    echo "<div class='row'>
            <div class='col-lg-4'></div>
            <div class='col-lg-4'>
              <div class='container'>Vous vous êtes déconnecté !</div></div></div>";
} else {
    // Affiche le script HTML du logo , du formulaire de recherche et du menu
    print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
    echo "<div class='row'>
            <div class='col-lg-4'></div>
            <div class='col-lg-4'>
              <div class='container'>Vous êtes déconnecté ! " . $_COOKIE['the_username'];
    echo '</div></div></div></body>
    </html>';
}


?>
