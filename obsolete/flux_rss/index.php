<?php
    // Firefox vient d'enlever son support du format RSS en décembre 2018, c'est pourquoi il n'est pas possible de le lire avec Firefox.
    //header("Content-Type: application/rss+xml; charset=UTF-8");
    //ISO-8859-1

    include("../connection_bdd.php");

    $server = "http://http://213.32.90.43/projet_tutore/upload_musiques/";

    $rssfeed = '<?xml version="1.0" encoding="UTF-8"?>';
    $rssfeed .= '<?xml-stylesheet type="text/css" href="http://http://213.32.90.43/projet_tutore/css/rss.css" ?>';
    $rssfeed .= '<rss version="2.0">';
    $rssfeed .= '<channel>';
    $rssfeed .= '<title>My RSS feed</title>';
    //$rssfeed .= '<link>http://www.mywebsite.com</link>';
    $rssfeed .= '<description>This is a simple RSS feed</description>';
    $rssfeed .= '<language>fr</language>';
    $rssfeed .= '<copyright>'.date("Y").'</copyright>';


    // Va chercher le morceau à écouter
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $stmt = $pdo->prepare("SELECT * FROM morceau ORDER BY id DESC");
    $stmt->execute();
    while ($row = $stmt->fetch()) {

        $rssfeed .= '<item>';
        $rssfeed .= '<title>' . $row["titre"] . '</title>';
        //$rssfeed .= '<description></description>';
        $rssfeed .= '<link>' . $server . $row["file_name"] . $row["extension"] . '</link>';
        $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O") . '</pubDate>';
        $rssfeed .= '</item>';
    }

    $rssfeed .= '</channel>';
    $rssfeed .= '</rss>';

    echo $rssfeed;
?>
