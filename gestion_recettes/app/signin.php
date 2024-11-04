<?php
session_start();
require 'connect.php';
$db = new PDO(DNS, LOGIN, PASSWORD, $options);
error_reporting(E_ALL);
ini_set("display_errors", 1);

if (isset($_POST['save'])) {
    if (!empty($_POST['last_name']) AND !empty($_POST['first_name']) AND !empty($_POST['email'])
    AND !empty($_POST['login']) AND !empty($_POST['pass1']) AND !empty($_POST['pass2'])) {

        $sqlexist = 'SELECT email FROM R_users WHERE email = ?';
        $userexist = $db->prepare($sqlexist);
        $userexist->execute(array($_POST['email']));
        $exist = $userexist->rowCount();

        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $login = $_POST['login'];
        $email = $_POST['email'];
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];

        if ($exist == 0) {

        if ($pass1 == $pass2) {

            $passhash = password_hash($pass1, PASSWORD_DEFAULT);

            $sqlinsert = 'INSERT INTO R_users (first_name, last_name, login, email, password, id_role)
            VALUES (:fn, :ln, :l, :e, :pass, 3)';
            $insertuser = $db->prepare($sqlinsert);
            $insertuser->bindParam('fn', $first_name);
            $insertuser->bindParam('ln', $last_name);
            $insertuser->bindParam('l', $login);
            $insertuser->bindParam('e', $email);
            $insertuser->bindParam('pass', $passhash);
            $insertuser->execute();

            $e = 'Les informations ont bien été enregistrées ! <a href="login.php">Connectez-vous</a>';

        } else {

            $e = 'Les mots de passe ne correspondent pas...';
        }

        } else {

            $e = 'L\'E-mail est déjà utilisée...';
        }

    } else {

        $e = 'Veuillez saisir tous les champs...';
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
    <title>Inscription</title>
</head>
<body>
<section class="main-content">
    <form method="POST">
        <h2>Inscription</h2>
        <label>Nom</label>
        <br>
        <input name="last_name" type="text">
        <br><br>
        <label>Prénom</label>
        <br>
        <input name="first_name" type="text">
        <br><br>
        <label>Login</label>
        <br>
        <input name="login" type="text">
        <br><br>
        <label>Adresse mail</label>
        <br>
        <input name="email" type="email">
        <br><br>
        <label>Mot de passe</label>
        <br>
        <input name="pass1" type="password">
        <br><br>
        <label>Confirmation mot de passe</label>
        <br>
        <input name="pass2" type="password">
        <br><br>
        <a class="form-link" href="login.php">Connexion</a><input class="form-btn" name="save" type="submit" value="Inscription">
        <br><br>
        <?php if (isset($e)){echo $e;} ?>
    </form>
</section>
</body>
</html>