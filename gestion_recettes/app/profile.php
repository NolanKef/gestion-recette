<?php
session_start();
require 'connect.php';
$db = new PDO(DNS, LOGIN, PASSWORD, $options);
error_reporting(E_ALL);
ini_set("display_errors", 1);

if ((isset($_SESSION['id_user']) AND $_SESSION['id_user'] > 0)) {
    $iduser = $_SESSION['id_user'];
    //$idrole = $_SESSION['id_role'];

    $getid = intval($_GET['id_user']);
    $statement = $db->prepare('SELECT * FROM R_users WHERE id_user = ?');
    $statement->execute(array($getid));
    $user = $statement->fetch();

    $sqltype = 'SELECT * FROM R_type';
    $stmttype = $db->prepare($sqltype);
    $stmttype->execute();

    $filter_type = "";
    if (isset($_POST['filter'])) {
        if($_POST['filtre'] == 1) {
            $filter_type = "AND type = 'Entrée'";
        } else if ($_POST['filtre'] == 2) {
            $filter_type = "AND type = 'PLat'";
        } else if ($_POST['filtre'] == 3) {
            $filter_type = "AND type = 'Sauce'";
        } else if ($_POST['filtre'] == 4) {
            $filter_type = "AND type = 'Dessert'";
        } else if ($_POST['filtre'] == 'all') {
            $sqlrecipe = "SELECT * FROM R_recipe WHERE id_user = '$getid' ORDER BY id_recipe DESC";
            $recipe = $db->prepare($sqlrecipe);
            $recipe->execute();
        }
    }

    $sqlrecipe = "SELECT * FROM R_recipe WHERE id_user = '$getid' $filter_type ORDER BY id_recipe DESC";
    $recipe = $db->prepare($sqlrecipe);
    $recipe->execute();
    $recipecount = $recipe->rowCount();

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
</head>
<body>
<title>Recettes de @<?= $user['login']; ?></title>
    <div class="nav-links">
    <?php if($user['id_role'] == 1){ ?>
    <a href="manage_users.php?id_user=<?= $user['id_user']?>"><img src="pics/users.png" width="25px"></a>
    <?php } ?>
    <a href="../app/logout.php?id_user=<?= $_SESSION['id_user'] ?>">Déconnexion</a>
    <a href="option.php?id_user=<?= $user['id_user']; ?>">Edition</a>
    </div>

    <section class="profile-content">
    <?php if ($user['is_nutri'] == 1) {$check = '<img src="pics/check.png" width="25px">';} else {$check = "";} ?>
    <h1><span>Recettes de</span> @<?= $user['login'].' '.$check; ?>
    <?php if($user['id_role'] == 1){echo '(Admin)';} ?></h1>
    <?php if ($_SESSION['id_user'] == $_GET['id_user']) { ?>
    <a class="add-btn" href="add_recipe.php?id_user=<?= $user['id_user']; ?>">Ajouter +</a>
    <?php } ?>
    <form class="form-filter" action="" method="POST">
        <select name="filtre">
        <option value="all">Tout</option>
        <?php
                while ($rowtype = $stmttype->fetch()) {
                    echo '<option value="'.$rowtype['id_type'].'">'.$rowtype['label'].'</option>';
                }
            ?>
        </select>
        <input type="submit" name="filter" value="Filtrer">
    </form>
    <ul>
    <?php if ($_SESSION['id_user'] == $_GET['id_user'] OR $_SESSION['id_role'] == 1) { ?>
    <?php
    while ($row = $recipe->fetch()) {
        if ($row['vegan'] == 1) {$vegan = "<span style='color: green; font-weight:bold;'>Vegan</span>";} else {$vegan = "";}

        echo '<li>'.$vegan.' <a href="recipe.php?id_user='.$getid.'&id_recipe='.$row['id_recipe'].'">'.$row['type'].' | '.$row['recipe_name'].'</a></li>';
        echo '<hr>';
    }
    if (isset($_POST['filter']) AND $recipecount == 0) {echo '<p class="empty">Aucune recette</p>';}
    ?>
    </ul>
    <?php } ?>
    </section>
</body>
</html>
<?php } ?>