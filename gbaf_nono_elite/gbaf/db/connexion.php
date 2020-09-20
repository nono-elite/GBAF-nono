<?php




// renommer ce fichier "connexion.php" et compléter avec vos identifiants
try {
    $db = new PDO('mysql:host=localhost;dbname=banque;charset=utf8','root','',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (\Exception $e) {
    die('Erreur :' . $e->getMessage());
}




?>