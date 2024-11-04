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

    $sql = 'SELECT * FROM R_users';
    $users = $db->prepare($sql);
    $users->execute();
    $nbusers = $users->rowCount();

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
<title>Gestion des utilisateurs</title>
    <div class="nav-links">
    <?php if($user['id_role'] == 1){ ?>
    <a href="profile.php?id_user=<?= $user['id_user']?>" target="_blank"><img src="pics/home.png" width="20px"></a>
    <?php } ?>
    <a href="logout.php?id_user=<?= $_SESSION['id_user']; ?>">Déconnexion</a>
    <a href="option.php?id_user=<?= $user['id_user']; ?>">Edition</a>
    </div>

    <section class="profile-content">
    <h1><span>Espace de</span> <?= $_SESSION['first_name']." ".$_SESSION['last_name']; ?>
    <?php if($user['id_role'] == 1){echo '(Admin)';} ?></h1>
    <a class="add-btn" href="add_user.php?id_user=<?= $user['id_user']; ?>">Ajouter +</a>
    <ul>
    <h1><?= $nbusers ?> <span>utilisateur(s)<span></h1>
    <?php
    while ($row = $users->fetch()) {
        if ($row['is_nutri'] == 1) {$check = '<img src="pics/check.png" width="20px">';} else {$check = "";}
        echo '<li><a href="profile.php?id_user='.$row['id_user'].'">'.$row['first_name'].' '.$row['last_name'].' '.$check.'</a></li>';
        echo '<hr>';
    }
    ?>
    </ul>
    </section>
</body>
</html>
<?php } ?>