<?php

include("../connection_bdd.php");

$texte = $_GET["texte"];
$id_user = $_COOKIE["the_id"];
$id_morceau = $_GET["id_morceau"];

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("INSERT INTO commentaire (id_morceau, id_user, texte)  VALUES ( :id_morceau, :id_user, :texte)");
$stmt->bindParam(':id_morceau', $id_morceau);
$stmt->bindParam(':id_user', $id_user);
$stmt->bindParam(':texte', $texte);
$stmt->execute();
$id_last = $pdo->lastInsertId();

$stmt = $pdo->prepare("SELECT * FROM commentaire WHERE id = ?");
$stmt->execute(array($id_last));
$row = $stmt->fetch();

// Va chercher l'auteur du commentaire
$auteur = "";
$stmt_ = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt_->execute(array($row["id_user"]));
while ($ligne = $stmt_->fetch()) {
    $auteur = $ligne["username"];
}
if($id_user != null) {
    echo $row["date"] . " : <strong>" . $auteur . "</strong> : " . $row["texte"];
}


?>
