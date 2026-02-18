<?php
session_start();

      try{
        $pdo = new PDO('mysql:host=localhost;dbname=crud_secu','root','');
    }catch(PDOException $e){
        die ('Erreur:'. $e->getMessage());
    }   
if(!isset($_POST['token_modif']) || $_POST['token_modif'] !== $_SESSION['token_titre_mofid']){
    die('Erreur: Token invalide');
}



if(isset($_POST['title']) && !empty($_POST['title'])){
    $titre = htmlspecialchars($_POST['title']);
    
}else{
    echo "<p>titre invalide</p>";
    exit;
}

if(isset($_POST['chanteur']) && !empty($_POST['chanteur'])){
    $chanteur = htmlspecialchars($_POST['chanteur']);
    
}else{
    echo "<p>chanteur est obligatoire</p>";
    exit;
}
$musique_id = (int) $_POST['musique_id'];
if(isset($titre)){

        $modif_titre = $pdo->prepare('UPDATE musique SET titre = :titre where musique_id = :musique_id  ');
        $modif_titre->execute([
            'titre' =>$titre,
            "musique_id" => $musique_id,
        ]);
        echo '<p>musqiue modifier avec succes</p>';
        header("location:index.php");
        unset($_SESSION['token_titre_mofid']);
        exit;
}

if(isset($chanteur)){

        $modif_titre = $pdo->prepare('UPDATE musique SET chanteur = :chanteur WHERE musique_id = :musique_id  ');
        $modif_titre->execute([
            'chanteur' =>$chanteur,
            "musique_id" => $musique_id,
        ]);
        echo '<p>chanteur modifier avec succes</p>';
        header("location:index.php");
        unset($_SESSION['token_titre_mofid']);
        exit;
}   