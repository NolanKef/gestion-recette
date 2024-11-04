<?php
session_start();
require 'connect.php';
$db = new PDO(DNS, LOGIN, PASSWORD, $options);
error_reporting(E_ALL);
ini_set("display_errors", 1);

if (isset($_SESSION['id_user']) AND $_SESSION['id_user'] > 0 && isset($_GET['id_recipe'])) {
    $iduser = $_SESSION['id_user'];
    $idrecipe = intval($_GET['id_recipe']);

    $getid = intval($_GET['id_user']);
    $statement = $db->prepare('SELECT * FROM R_users WHERE id_user = ?');
    $statement->execute(array($getid));
    $user = $statement->fetch();

    $sqlrecipe = "SELECT * FROM R_recipe WHERE id_user = '$iduser' AND id_recipe = '$idrecipe'";
    $rec = $db->prepare($sqlrecipe);
    $rec->execute();
    $recipe = $rec->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
    <title><?php if($recipe) {echo $recipe['recipe_name'];} ?></title>
</head>
<body>
<div class="nav-links">
    <a href="profile.php?id_user=<?= $user['id_user'] ?>">Liste des recettes</a>
    </div>
<section class="recipe-content">
    <h3><span><?php if($recipe) {echo $recipe['recipe_name'];} ?></span></h3>
    <h4><?php if($recipe) {echo $recipe['type'];} ?></h4>
    <h5>Ingrédients</h5>
    <div class="ingredient-content">
    <p><?php if($recipe) {echo nl2br($recipe['ingredient']);} ?></p>
    </div>
    <h5>Préparation</h5>
    <div class="preparation-content">
    <p><?php if($recipe) {echo nl2br($recipe['recipe_content']);} ?></p>
</div>
</section>
</body>
</html>
<?php } ?>