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

    $sqltype = 'SELECT * FROM R_type';
    $stmttype = $db->prepare($sqltype);
    $stmttype->execute();

    $sqlrecipe = "SELECT * FROM R_recipe WHERE id_user = '$iduser' AND id_recipe = '$idrecipe'";
    $rec = $db->prepare($sqlrecipe);
    $rec->execute();
    $recipe = $rec->fetch();

    if (isset($_POST['save'])) {
        if (!empty($_POST['title']) AND !empty($_POST['content']) AND !empty($_POST['type'])) {

            $title = $_POST['title'];
            $content = $_POST['content'];
            $ingredient = $_POST['ingredient'];

            $sql = "UPDATE R_recipe SET recipe_name = :t, ingredient = :i, recipe_content = :c, type = :type WHERE id_recipe = '$idrecipe'";
            $statement = $db->prepare($sql);
            $statement->bindParam('t', $title);
            $statement->bindParam('c', $content);
            $statement->bindParam('i', $ingredient);
            $statement->bindParam('type', $_POST['type']);
            $statement->execute();

            header('Location: profile.php?id_user='.$iduser);

            $e = 'La recette a bien été modifiée !';

        } else {

            $e = 'Veuillez saisir un titre et un contenu';
        }

    }

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
    <title>Modifier une recette</title> 
</head>
<body>
    <div class="nav-links">
    <a href="profile.php?id_user=<?= $user['id_user'] ?>">Liste des recettes</a>
    <a href="">Déconnexion</a>
    </div>
<section class="main-content">
    <form action="" method="POST">
    <h2>Modifier une recette</h2>
    <br><br>
        <label>Intitulé de la recette</label>
        <br>
        <input name="title" type="text" placeholder="Titre" value="<?php if($recipe) {echo $recipe['recipe_name'];} ?>">
        <br><br>
        <label>Type de recette</label>
        <br>
        <select name="type">
            <?php
                while ($rowtype = $stmttype->fetch()) {
                    echo '<option value="'.$rowtype['type'].'">'.$rowtype['type'].'</option>';
                }
            ?>
        </select>
        <br><br>
        <label>Ingrédients</label>
        <br>
        <textarea class="ingredient" name="ingredient" placeholder="Liste des ingrédients..."><?php if($recipe) {echo $recipe['ingredient'];} ?></textarea>
        <br><br>
        <label>Contenu de la recette</label>
        <br>
        <textarea name="content" placeholder="Contenu de la recette..."><?php if($recipe) {echo $recipe['recipe_content'];} ?></textarea>
        <br><br>
        <?php if(isset($e)) { echo $e.'<br><br>'; } ?>
        <input class="add-btn" name="save" type="submit" value="Modifier la recette">
        <br><br>
    </form>
</section>
</body>
</html>
<?php } ?>