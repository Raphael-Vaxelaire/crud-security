<?php

if (!isset($_GET['s']) || empty($_GET['s'])){
    die('Erreur : slug non fourni');
}

$slug = htmlspecialchars($_GET['s']);

try{
    $pdo = new PDO('mysql:host=localhost;dbname=crud_secu','root','');
}catch(PDOException $e){
    die ('Erreur:'. $e->getMessage());
}

$verif_slug = $pdo->prepare('SELECT * FROM musique WHERE slug = :slug');
$verif_slug->execute(['slug'=>$slug]);
if($verif_slug->rowCount() ==0){
    die('Erreur : Article non trouvé');
}

$article = $verif_slug->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= htmlspecialchars($article['titre']) ?></h1>
<p><?= htmlspecialchars($article['chanteur']) ?></p>
    <a href="index.php">Retour à l'accueil</a>
</body>
</html>
