<?php

include("../connection_bdd.php");

if (isset($_GET["idMusique"]) && isset($_GET["idUser"])) {
    $id_user = $_GET["idUser"];
    $id_musique = $_GET["idMusique"];
    
    // Update database
    $pdo = new PDO("mysql:host=$db_host; dbname=$db_name", $db_user, $db_password);
    $stmt = $pdo->prepare("INSERT INTO aimer (id_morceau, id_user)  VALUES ( :id_morceau, :id_user)");
    $stmt->bindParam(':id_morceau', $id_musique);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->execute();

    echo "<button type='button' onclick='jaimePas(likeFunction)'>J'aime déjà</button>";
}

?>
