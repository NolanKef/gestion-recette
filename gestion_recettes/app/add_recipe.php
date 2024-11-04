<?php
session_start();
require 'connect.php';
$db = new PDO(DNS, LOGIN, PASSWORD, $options);
error_reporting(E_ALL);
ini_set("display_errors", 1);

if (isset($_SESSION['id_user']) AND $_SESSION['id_user'] > 0) {
    $iduser = $_SESSION['id_user'];
    $getid = intval($_GET['id_user']);
    $statement = $db->prepare('SELECT * FROM R_users WHERE id_user = ?');
    $statement->execute(array($getid));
    $user = $statement->fetch();

    $sqltype = "SELECT * FROM R_type WHERE id_type <> 5";
    $stmttype = $db->prepare($sqltype);
    $stmttype->execute();

    if (isset($_POST['save'])) {
        if (!empty($_POST['title']) AND !empty($_POST['content']) AND !empty($_POST['type'])
        AND !empty($_POST['vegan']) AND !empty($_POST['view'])) {

            $title = $_POST['title'];
            $content = $_POST['content'];
            $view = $_POST['view'];
            $vegan = $_POST['vegan'];

            $sql = "INSERT INTO R_recipe (recipe_name, recipe_content, view, date_add, id_user, id_type, vegan)
            VALUES (:t, :c, :view, NOW(), '$iduser', :type, :v)";
            $statement = $db->prepare($sql);
            $statement->bindParam('t', $title);
            $statement->bindParam('c', $content);
            $statement->bindParam('view', $view);
            $statement->bindParam('type', $_POST['type']);
            $statement->bindParam('v', $vegan);
            $statement->execute();

            $e = 'La recette a bien été ajoutée !';

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
    <title>Ajouter une recette</title> 
</head>
<body>
    <div class="nav-links">
    <a href="profile.php?id_user=<?= $user['id_user'] ?>">Liste des recettes</a>
    <a href="">Déconnexion</a>
    </div>
<section class="main-content">
    <form action="" method="POST">
    <h2>Ajouter une recette</h2>
    <br><br>
        <label>Intitulé de la recette</label>
        <br>
        <input name="title" type="text" placeholder="Titre">
        <br><br>
        <label>Type de recette</label>
        <br>
        <select name="type">
            <?php
                while ($rowtype = $stmttype->fetch()) {
                    echo '<option value="'.$rowtype['label'].'">'.$rowtype['label'].'</option>';
                }
            ?>
        </select>
        <br><br>
        <label>Végan</label>
        <br>
        <label>Oui</label>
        <input type="radio" name="vegan" value="1">
        <label>Non</label>
        <input type="radio" name="vegan" value="0">
        <br><br>
        <label>Visibilité</label>
        <br>
        <label>Publique</label>
        <input type="radio" name="view" value="1">
        <label>Privée</label>
        <input type="radio" name="view" value="0">
        <br><br>
        <label>Contenu de la recette</label>
        <br>
        <textarea name="content" placeholder="Contenu de la recette..."></textarea>
        <br><br>
        <?php if(isset($e)) { echo $e.'<br><br>'; } ?>
        <input class="add-btn" name="save" type="submit" value="Ajouter la recette">
        <br><br>
    </form>
</section>
</body>
</html>
<?php } ?>