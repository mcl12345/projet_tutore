<?php

session_start();

include("../connection_bdd.php");

$id_user = $_SESSION["the_id"];
$id_musique = $_GET["idMusique"];
$texte = $_GET["texte"];
$date_ = date("Y-m-d h:m:s");

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
$stmt = $pdo->prepare("INSERT INTO commentaire (id_morceau, id_user, texte, date_)  VALUES ( :id_morceau, :id_user, :texte, :date_)");
$stmt->bindParam(':id_morceau', $id_musique);
$stmt->bindParam(':id_user', $id_user);
$stmt->bindParam(':texte', $texte);
$stmt->bindParam(':date_', $date_);
$stmt->execute();
$id_last = $pdo->lastInsertId();

$stmt = $pdo->prepare("SELECT * FROM commentaire WHERE id = ?");
$stmt->execute(array($id_last));
$row = $stmt->fetch();

// Récupère l'auteur du commentaire
$auteur = "";
$stmt_ = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt_->execute(array($row["id_user"]));
while ($ligne = $stmt_->fetch()) {
    $auteur = $ligne["username"];
}
if($id_user != null) {
    echo $row["date_"] . " : <strong>" . $auteur . "</strong> : " . $row["texte"];
}

?>
