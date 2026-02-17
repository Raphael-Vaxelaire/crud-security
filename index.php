<?php
session_start();
//creation token
if(!isset($_SESSION['token_titre_add']) || empty($_SESSION['token_titre_add'])){
    $_SESSION['token_titre_add'] = bin2hex(random_bytes(32));
};

// Traiter uniquement si formulaire soumis
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
if(!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token_titre_add']){
    die('Erreur: Token invalide');
}
unset($_SESSION['token_titre_add']);


if(isset($_POST['title']) && !empty($_POST['title'])){
    $titre = $_POST['title'];
    
}else{
    echo "<p>titre invalide</p>";
    exit;
}

if(isset($_POST['slug']) && !empty($_POST['slug'])){
    $slug =$_POST['slug'];

}else{
        echo '<p>Le slug est obligatoire</p>';
        exit;
}

if(isset($_POST['chanteur']) && !empty($_POST['chanteur'])){
    $chanteur = $_POST['chanteur'];
    
}else{
    echo "<p>chanteur est obligatoire</p>";
    exit;
}

if(isset($titre)&& isset($slug)&& isset($chanteur)){
     try{
        $pdo = new PDO('mysql:host=localhost;dbname=crud_secu','root','');
    }catch(PDOException $e){
        die ('Erreur:'. $e->getMessage());
    }

    $verif_slug = $pdo->prepare('SELECT * FROM musique WHERE slug = :slug');
    $verif_slug->execute(['slug'=>$slug]);
    if($verif_slug->rowCount()>0){
        echo '<p>le Slug est deja utilisé</p>';
    }else{
        $insert = $pdo->prepare('INSERT INTO musique(titre,chanteur,slug) VALUES (:titre,:chanteur,:slug)');
        $insert->execute([
            'titre' =>$titre,
            'chanteur' => $chanteur,
            'slug' => $slug
        ]);
        echo '<p>musqiue ajouté avec succes</p>';
        header("location:index.php");

}
}
}

 try{
        $pdo = new PDO('mysql:host=localhost;dbname=crud_secu','root','');
    }catch(PDOException $e){
        die ('Erreur:'. $e->getMessage());
    }


//Read toute les musiques
    $stmt = $pdo->prepare('SELECT * FROM musique');
    $stmt->execute(); 
    $read = $stmt->fetchAll(PDO::FETCH_ASSOC);
//supprimer une musique

if(isset($_GET['action']) && $_GET['action'] == 'delete') {

    $musique_id = $_GET['musique_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM musique WHERE musique_id = :musique_id");

        $stmt->execute([
            "musique_id" => $musique_id,
        ]);
        header("location:index.php");

    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}

//modifier une musique




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="hidden" name="token" value="<?= $_SESSION['token_titre_add'];?>">
        <label for="title">Titre</label>
        <input type="text" name="title" id="title">
        <br>
        <label for="slug">Slug</label>
        <input type="text" name="slug" id="slug">
        <br>
        <label for="chanteur">chanteur</label>
        <input type="text" name="chanteur" id="chanteur">
        <br>
        <button type="submit">Ajouter</button>
    </form>

     <table border="1">
    <tbody>
    <?php 
            
            foreach ($read as $key => $reads) {
                echo "<tr>";
                    echo "<td>" . $reads["titre"] . "</td>";
                    echo "<td>" . $reads["chanteur"] . "</td>";
                    echo "<td> <a href='?musique_id=". $reads["musique_id"] . "&action=delete'> Supprimer </a> </td>"; 
                echo "</tr>";
            }
            
        ?>

        </tbody>
        </table>
</body>
</html>