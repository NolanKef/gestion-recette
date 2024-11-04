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

    $sqlrole = 'SELECT * FROM R_role';
    $stmtrole = $db->prepare($sqlrole);
    $stmtrole->execute();

    if (isset($_POST['save'])) {
        if (!empty($_POST['last_name']) AND !empty($_POST['first_name']) AND !empty($_POST['login'])
        AND !empty($_POST['email']) AND !empty($_POST['password']) AND !empty($_POST['role'])
        AND !empty($_POST['nutri'])) {

            $last_name = $_POST['last_name'];
            $first_name = $_POST['first_name'];
            $login = $_POST['login'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $nutri = $_POST['nutri'];

            $passhash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO R_users (first_name, last_name, login, email, password, is_nutri, id_role)
            VALUES (:fn, :ln, :l, :e, :pass, :n, :r)";
            $statement = $db->prepare($sql);
            $statement->bindParam('fn', $first_name);
            $statement->bindParam('ln', $last_name);
            $statement->bindParam('l', $login);
            $statement->bindParam('e', $email);
            $statement->bindParam('pass', $passhash);
            $statement->bindParam('r', $role);
            $statement->bindParam('n', $nutri);
            $statement->execute();

            $e = 'L\'utilisateur a bien été ajoutée !';

        } else {

            $e = 'Veuillez saisir toutes les informations';
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
    <title>Ajouter un utilisateur</title> 
</head>
<body>
    <div class="nav-links">
    <a href="profile.php?id_user=<?= $user['id_user'] ?>">Liste des recettes</a>
    <a href="../app/logout.php?id_user=<?= $_SESSION['id_user'] ?>">Déconnexion</a>
    </div>
<section class="main-content">
    <form action="" method="POST">
    <h2>Ajouter un utilisateur</h2>
    <br>
        <label>Nom</label>
        <br>
        <input name="last_name" type="text" placeholder="Nom">
        <br><br>
        <label>Prénom</label>
        <br>
        <input name="first_name" type="text" placeholder="Prénom">
        <br><br>
        <label>Pseudo</label>
        <br>
        <input name="login" type="text" placeholder="Pseudo">
        <br><br>
        <label>Adresse E-mail</label>
        <br>
        <input name="email" type="email" placeholder="E-mail">
        <br><br>
        <label>Mot de passe</label>
        <br>
        <input name="password" type="password" placeholder="Mot de passe">
        <br><br>
        <label>Rôle</label>
        <br>
        <select name="role">
            <?php
                while ($rowrole = $stmtrole->fetch()) {
                    echo '<option value="'.$rowrole['id_role'].'">'.$rowrole['role'].'</option>';
                }
            ?>
        </select>
        <br><br>
        <label>Nutritionniste ?</label>
        <br><br>
        <label>Oui</label>
        <input type="radio" name="nutri" value="1">
        <label>Non</label>
        <input type="radio" name="nutri" value="0">
        <br><br>
        <?php if(isset($e)) { echo $e.'<br><br>'; } ?>
        <input class="add-btn" name="save" type="submit" value="Ajouter l'utilisateur">
        <br><br>
    </form>
</section>
</body>
</html>
<?php } ?>