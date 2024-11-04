<?php
    // Après "dbname=" ajouter le nom de la bdd
    define('DNS', 'mysql:host=localhost;dbname=gestion_recettes');
    // Ajouter le login et le mot de passe des identifiants d'accès à la bdd
    define('LOGIN', 'nolan');
    define ('PASSWORD', 'Nolankeffala143#');
    $options = array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                     PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
                     PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8");
?>
