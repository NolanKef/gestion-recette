<?php
session_start();
require 'connect.php';
$db = new PDO(DNS, LOGIN, PASSWORD, $options);

if (isset($_GET['id_recipe']) AND !empty($_GET['id_recipe'])) {
    $supprid = htmlspecialchars($_GET['id_recipe']);

    $suppr = $db->prepare('DELETE FROM R_recipe WHERE id_recipe = ?');
    $suppr->execute(array($supprid));

    header("Location: option.php?id_user=".$_SESSION['id_user']);
}

?>