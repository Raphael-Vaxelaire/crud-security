<?php
session_start();
if(!isset($_SESSION['user_add']) || empty($_SESSION['user_add'])){
    $_SESSION['user_add'] = bin2hex(random_bytes(32));
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="inscription.php" method="post">
        <input type="hidden" name="userr" value="<?= $_SESSION['user_add'];?>">
        <label for="title">Titre</label>
        <input type="text" name="title" id="title">
        <br>
        <label for="slug">Slug</label>
        <input type="text" name="slug" id="slug">
        <br>
        <label for="content">Contenu</label>
        <textarea type="text" name="content" id="content"></textarea>
        <br>
        <button type="submit">Ajouter</button>
    </form>
    <a href="connexion.php"></a>
</body>
</html>