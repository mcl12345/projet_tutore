<?php

include("../connection_bdd.php");
include("../logo_search_menu/index.php");

print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);

// -------------------------------------

echo "<div class='row'>
    <div class='col-lg-4'></div>
    <div class='col-lg-4'>
      <div class='container'>";

echo "<form action='./?id_morceau=".$_GET["id_morceau"]."' method='post'>";
echo "<label for='genre'>Genre :</label>
<select name='id_genre'>";
$id_artiste = 0;
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->query("SELECT * FROM genre");
while ($row = $stmt->fetch()) {
  echo "<option value='".$row['id']."'>".$row['nom']."</option>";
}
echo "</select>
<input type='submit' value='Ajouter' /><br />";
echo "</form>";


if( isset ($_POST["id_genre"]) && isset ($_GET["id_morceau"]) && $_POST["id_genre"] != 0 && $_GET["id_morceau"] != 0) {
    $stmt = $pdo->prepare("INSERT INTO morceau_genre (id_morceau, id_genre)  VALUES (:id_morceau, :id_genre)");
    $stmt->bindParam(':id_morceau', $_GET["id_morceau"]);
    $stmt->bindParam(':id_genre', $_POST["id_genre"]);
    $stmt->execute();

    echo "Le genre a été correctement ajouté au morceau";
}

echo   '</div>
  </div>
</div>
</body>
  </html>';

?>
