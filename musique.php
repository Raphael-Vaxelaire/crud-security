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

<h1><?= htmlspecialchars($article['titre']) ?></h1>
<p><?= htmlspecialchars($article['chanteur']) ?></p>