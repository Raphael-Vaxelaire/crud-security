<?php

session_start();
//creation token
if(!isset($_SESSION['user_add']) || empty($_SESSION['user_add'])){
    $_SESSION['user_add'] = bin2hex(random_bytes(32));
};

if($_SERVER['REQUEST_METHOD'] === 'POST'){
 
if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = $_POST['email'];

} else {
    echo "<p>Email invalide</p>";
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<p>Email invalide</p>";
    exit;
}
if (isset($_POST['password']) && !empty($_POST['password'])) {
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, []);
} else {
    echo '<p>Le mot de passe est obligatoire</p>';
    exit;
}

if ($email && $password) {

        try{
        $pdo = new PDO('mysql:host=localhost;dbname=crud_secu','root','');
    }catch(PDOException $e){
        die ('Erreur:'. $e->getMessage());
    }
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->execute(["email" => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user && password_verify($password, $user['password'])) {
        $_SESSION["user"] = [
                "iduser" => $user["id"],
                "email" => $user["email"],
                "role" =>$user["role"]
            ];
        header("location:index.php");
        exit;
    } else {
        echo "La connexion a échoué !";
    }

   unset($_SESSION['user_add']);

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
    <form method="POST">
        <input type="hidden" name="userr" value="<?= $_SESSION['user_add']; ?>">
        <input class="email" name="email" type="email" placeholder="Email" id="emailC">
        <input class="password" name="password" type="password" placeholder="password">
        <input class="envoie margt" type="submit" value="Se connecter" id="valdiation">
    </form>
</body>

</html>