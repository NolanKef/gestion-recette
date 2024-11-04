<?php
session_start();
require 'connect.php';
$db = new PDO(DNS, LOGIN, PASSWORD, $options);
error_reporting(E_ALL);
ini_set("display_errors", 1);

if ((isset($_SESSION['id_user']) AND $_SESSION['id_user'] > 0)) {
    $iduser = $_SESSION['id_user'];

    $getid = intval($_GET['id_user']);
    $statement = $db->prepare('SELECT * FROM R_users WHERE id_user = ?');
    $statement->execute(array($getid));
    $user = $statement->fetch();

    $sqlrecipe = "SELECT * FROM R_recipe WHERE id_user = '$iduser'";
    $recipe = $db->prepare($sqlrecipe);
    $recipe->execute();

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
    <title>Édition</title>
</head>
<body>
<div class="nav-links">
<a href="profile.php?id_user=<?= $user['id_user']; ?>">Retour</a>
</div>
<section class="profile-content">
    <h1><span>Liste de vos recettes</span></h1>
    <ul>
    <?php
        while ($row = $recipe->fetch()) {
            echo '<li><a href="del.php?id_recipe='.$row['id_recipe'].'"><img src="pics/bin.png" width="20px"></a>
            <a href="edit.php?id_user='.$getid.'&id_recipe='.$row['id_recipe'].'"><img src="pics/edit.png" width="20px"></a> '.$row['recipe_name'].'</li>';
        }
    ?>
    </ul>
</section>
</body>
</html>
<?php } ?>