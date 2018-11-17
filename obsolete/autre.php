<?php
$db_host = "localhost";
$db_user = "phpmyadmin";
$db_password = "root";
$db_name = "projet_tutore3";

try {
    $my_username = 'Joe';
    $my_email = 'morvan.calmel@gmail.com';
    $my_password_encoded = md5('ehA15apv');

   $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
   // set the PDO error mode to exception
   //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   //$sql = "INSERT INTO user (username, email, password_encoded) VALUES ($my_username, $my_email, $my_password_encoded)";
   // use exec() because no results are returned
   //$pdo->exec($sql);
   $stmt = $pdo->prepare("INSERT INTO user (username, email, password_encoded)  VALUES (:username, :email, :password_encoded)");
   $stmt->bindParam(':username', $my_username);
   $stmt->bindParam(':email', $my_email);
   $stmt->bindParam(':password_encoded', $my_password_encoded);
   $stmt->execute();
   echo "Enregistrement effectué avec succès !<br />";
}
catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}
$pdo = null;

?>
