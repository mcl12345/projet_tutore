<?php

include("connection_bdd.php");

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("DELETE FROM aimer WHERE id_morceau = ? AND id_user = ?");
$stmt->execute(array($_GET["id_morceau"], $_GET["id_user"]));

echo '<button type="button" onclick="jaime(myFunction)">Jaime</button>';

?>
