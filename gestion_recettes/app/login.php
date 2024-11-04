<?php
session_start();
require 'connect.php';
$db = new PDO(DNS, LOGIN, PASSWORD, $options);
error_reporting(E_ALL);
ini_set("display_errors", 1);

if (isset($_POST['save'])) {
    if (!empty($_POST['email']) AND !empty($_POST['pass'])) {

        $email = $_POST['email'];
        $pass = $_POST['pass'];

        $statement = $db->prepare('SELECT * FROM R_users WHERE email = ?');
        $statement->execute(array($email));
        $user = $statement->fetch();

        if ($user && password_verify($pass, $user['password'])) {
            
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['login'] = $user['login'];

            header('Location: profile.php?id_user='.$user['id_user']);

        } else {

            $e = 'Adresse mail ou mot de passe invalide...';

        }

    } else {

        $e = 'Veuillez saisir votre e-mail et mot de passe...';
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
    <title>Connexion</title>
</head>
<body>
<section class="main-content">
<form method="POST">
        <h2>Connexion</h2>
        <label>Adresse mail</label>
        <br>
        <input name="email" type="email">
        <br><br>
        <label>Mot de passe</label>
        <br>
        <input name="pass" type="password">
        <br><br>
        <a class="form-link" href="signin.php">Inscription</a><input class="form-btn" name="save" type="submit" value="Connexion">
        <br><br>
        <?php if (isset($e)){echo $e;} ?>
    </form>
</section>
</body>
</html>