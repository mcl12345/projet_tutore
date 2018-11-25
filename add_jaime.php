<?php

include("connection_bdd.php");

if( isset($_GET["id_morceau"]) && isset($_GET["id_user"])) {
    $id_morceau = $_GET["id_morceau"];
    $id_user = $_GET["id_user"];
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $stmt = $pdo->prepare("INSERT INTO aimer (id_morceau, id_user)  VALUES ( :id_morceau, :id_user)");
    $stmt->bindParam(':id_morceau', $id_morceau);
    $stmt->bindParam(':id_user', $id_user);
    $stmt->execute();

    /*$con = mysqli_connect($db_host, $db_user, $db_password, $db_name);
    if (!$con) {
        die('Could not connect: ' . mysqli_error($con));
    }

    $sql= "INSERT INTO aimer (id_morceau, id_user)  VALUES ( '$id_morceau', '$id_user')";
    $result = mysqli_query($con, $sql);

    if(! $result ) {
        die('Could not enter data: ' . mysql_error());
    }

    mysqli_close($con);*/

    echo '<button type="button" onclick="jaimePas(myFunction)">Jaime déjà</button>';
}

?>
