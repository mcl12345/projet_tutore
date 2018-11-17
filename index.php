<?php

include("connection_bdd.php");
include("logo_search_menu2.php");


// Lance le script HTML d'affichage
print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

/** Contenu ici
**/


echo '</body>
</html>';

?>
