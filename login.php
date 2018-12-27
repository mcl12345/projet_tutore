<?php

include("connection_bdd.php");
include("logo_search_menu2.php");

function formulaire_login_HTML() {
    echo "<div class='row'>
            <div class='col-lg-4'></div>
            <div class='col-lg-4'>
              <div class='container'><br /><br /><br /><br /><br /><br /><br />
                <form method='post' action='login.php'>
                  <label class='label_formulaire' for='username'>Username : </label><input id='username' name='username' type='text' required /><br />
                  <label class='label_formulaire' for='plain_password'>Password : </label><input id='plain_password' name='plain_password' id='plain_password' type='password' required /><br /><br />
                  <input type='submit' style='margin-left:100px;' value='Connexion' />
                </form>
              </div>
            </div>
        </div>";
}


// Ici on vérifie si l'utilisateur s'est correctement connecté
if( !empty($_POST["username"]) &&
    !empty($_POST["plain_password"])) {

      // On sauvegarde dans des variables :
      $my_username = $_POST['username'];
      $my_password_encoded = md5($_POST['plain_password']);
      $my_email = "";
      $my_role = "";
      $my_id = 0;

      // Verification du bon password selon l'username donné
      $bool_verification_password = false;
      try {
          $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
          $stmt = $pdo->prepare("SELECT * FROM user where username = ?");
          if ($stmt->execute(array($my_username))) {
            while ($row = $stmt->fetch()) {
              if($row['password_encoded'] == $my_password_encoded) {
                  $bool_verification_password = true;
                  $my_email = $row['email'];
                  $my_role = $row['role'];
                  $my_id = $row["id"];
              }
            }
          }
      } catch(PDOException $e) {
          echo $sql . "<br>" . $e->getMessage();
      }

      if( $bool_verification_password) {
          // Insertion COOKIES
          setcookie("the_id", $my_id);
          setcookie("the_username", $my_username);
          setcookie("the_email", $my_email);
          setcookie("the_password_encoded", $my_password_encoded);
          setcookie("the_role", $my_role);

          /* Une erreur se produira si il y a une sortie au dessus, qui se trouve avant l'appel à la fonction header() */
          header('Location: login.php');
          /*print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
          echo "<div class='row'>
                  <div class='col-lg-4'></div>
                  <div class='col-lg-4'>
                    <div class='container'>Vous êtes bien authentifié en tant que " . $my_username . " !";
          echo   '</div></div></div></body>
              </html>';
              */

      } else {
            print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
            echo "Mauvais password<br />";
            formulaire_login_HTML();
            echo   '</body>
              </html>';
      }
}
else if (isset($_COOKIE['the_username'])) {
    print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
    echo "<div class='row'>
            <div class='col-lg-4'></div>
            <div class='col-lg-4'><div class='container'>Bienvenue " . $_COOKIE["the_username"] . " !<br />
            
            </div>";
    echo   '</div></div></body>
      </html>';
} else {
    print_LOGO_FORMSEARCH_MENU($db_host, $db_name, $db_user, $db_password);
    formulaire_login_HTML();
    echo   '</body>
      </html>';
}

?>
