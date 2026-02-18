<?php 




if(isset($_POST['delete']) ){

try{
    $pdo = new PDO('mysql:host=localhost;dbname=crud_secu','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die('Erreur BDD : '.$e->getMessage());
}

    $musique_id = (int) $_POST['musique_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM musique WHERE musique_id = :musique_id");

        $stmt->execute([
            "musique_id" => $musique_id,
        ]);
        header("location:index.php");
        exit;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}