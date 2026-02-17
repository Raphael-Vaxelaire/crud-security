<?php

session_start();
//creation token
if(!isset($_SESSION['user_add']) || empty($_SESSION['user_add'])){
    $_SESSION['user_add'] = bin2hex(random_bytes(32));
};

// Traiter uniquement si formulaire soumis
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    unset($_SESSION['user_add']);
if(!isset($_POST['userr']) || $_POST['userr'] !== $_SESSION['user_add']){
    die('Erreur: Token invalide');
}



if(isset($_POST['email']) && !empty($_POST['email'])){
    $email = $_POST['email'];
    
}else{
    echo "<p>Email invalide</p>";
    exit;
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo "<p>Email invalide</p>";
    exit;
}
if(isset($_POST['password']) && !empty($_POST['password'])){
    $password =$_POST['password'];
    $hashedPassword = password_hash($password,PASSWORD_BCRYPT,[]);
}else{
        echo '<p>Le mot de passe est obligatoire</p>';
        exit;
}

if(isset($_POST['role']) && !empty($_POST['role'])){
    $role = $_POST['role'];
    
}else{
    echo "<p>role invalide</p>";
    exit;
}
if(isset($email)&& isset($password)&& isset($hashedPassword)){
    try{
        $pdo = new PDO('mysql:host=localhost;dbname=crud_secu','root','');
    }catch(PDOException $e){
        die ('Erreur:'. $e->getMessage());
    }
    $check = $pdo->prepare('SELECT id FROM user WHERE email = :email');
    $check->execute(['email' => $email]);
    if($check->fetch()){
        echo "<p>Cet email est déjà utilisé</p>";
        exit;
    }

    $insert = $pdo->prepare('INSERT INTO user(email,password,role) VALUES (:email,:password,:role)');
    $insert->execute([
        'email' =>$email,
        'password' => $hashedPassword,
        'role' =>$role
    ]);
    echo '<p>user ajouté avec succes</p>';
    header("location:connexion.php");

}
}



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
        <label for="email">Email</label>
        <input required type="email" name="email" id="email">
        <br>
        <label for="password">mot de passe</label>
        <input required type="password" name="password" id="password">
        <br>
        <label for="role">Rôle</label>
        <select name="role" id="role" required>
            <option value="user" selected>Utilisateur</option>
            <option value="admin">Administrateur</option>
          
        </select>
        <button type="submit">Ajouter</button>
    </form>
    <a href="connexion.php"></a>
</body>
</html>