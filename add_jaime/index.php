<?php

include("../connection_bdd.php");

if (isset($_GET["id_morceau"]) && isset($_GET["id_user"])) {
    // Update database
    $id_morceau = $_GET["id_morceau"];
    $id_user = $_GET["id_user"];
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $stmt = $pdo->prepare("INSERT INTO aimer (id_morceau, id_user)  VALUES ( :id_morceau, :id_user)");
    $stmt->bindParam(':id_morceau', $id_morceau);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->execute();

    echo '<button type="button" onclick="jaimePas(likeFunction)">Jaime déjà</button>';
}

?>
