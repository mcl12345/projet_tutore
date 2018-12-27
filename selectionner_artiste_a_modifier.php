<?php

include("connection_bdd.php");
include("logo_search_menu2.php");

print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

// -------------------------------------

echo "<div class='row'>
    <div class='col-lg-4'></div>
    <div class='col-lg-4'>
      <div class='container'>";

echo "<form action='modifier_artiste.php' method='get'>";
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$confirmation = false;
$stmt = $pdo->prepare("SELECT * FROM artiste");
$stmt->execute();
while ($row = $stmt->fetch()) {
  echo "<label for='id_artiste'>".$row["pseudonyme"]."</label> &nbsp&nbsp<input value='".$row['id']."' id='id_artiste' name='id_artiste' type='radio' /> <br />";
}
echo "<input value='Sélectionner' type='submit' />";
echo "</form>";


echo   '</div>
  </div>
</div>
</body>
  </html>';

?>
