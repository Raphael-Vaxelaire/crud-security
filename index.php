<?php
session_start();

//creation token
if (!isset($_SESSION['token_titre_add']) || empty($_SESSION['token_titre_add'])) {
    $_SESSION['token_titre_add'] = bin2hex(random_bytes(32));
}
;



if (!isset($_SESSION['token_titre_mofid']) || empty($_SESSION['token_titre_mofid'])) {
    $_SESSION['token_titre_mofid'] = bin2hex(random_bytes(32));
}
;
// Traiter uniquement si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token_titre_add']) {
        die('Erreur: Token invalide');
    }



    if (isset($_POST['title']) && !empty($_POST['title'])) {
        $titre = htmlspecialchars($_POST['title']);

    } else {
        echo "<p>titre invalide</p>";
        exit;
    }

    if (isset($_POST['slug']) && !empty($_POST['slug'])) {
        $slug = htmlspecialchars($_POST['slug']);

    } else {
        echo '<p>Le slug est obligatoire</p>';
        exit;
    }

    if (isset($_POST['chanteur']) && !empty($_POST['chanteur'])) {
        $chanteur = htmlspecialchars($_POST['chanteur']);

    } else {
        echo "<p>chanteur est obligatoire</p>";
        exit;
    }

    if (isset($titre) && isset($slug) && isset($chanteur)) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=crud_secu', 'root', '');
        } catch (PDOException $e) {
            die('Erreur:' . $e->getMessage());
        }

        $verif_slug = $pdo->prepare('SELECT * FROM musique WHERE slug = :slug');
        $verif_slug->execute(['slug' => $slug]);
        if ($verif_slug->rowCount() > 0) {
            echo '<p>le Slug est deja utilisé</p>';
        } else {
            $insert = $pdo->prepare('INSERT INTO musique(titre,chanteur,slug) VALUES (:titre,:chanteur,:slug)');
            $insert->execute([
                'titre' => $titre,
                'chanteur' => $chanteur,
                'slug' => $slug
            ]);
            echo '<p>musqiue ajouté avec succes</p>';
            header("location:index.php");
            unset($_SESSION['token_titre_add']);
            exit;
        }
    }
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=crud_secu', 'root', '');
} catch (PDOException $e) {
    die('Erreur:' . $e->getMessage());
}


//Read toute les musiques
$stmt = $pdo->prepare('SELECT * FROM musique');
$stmt->execute();
$read = $stmt->fetchAll(PDO::FETCH_ASSOC);



//role
$role = $_SESSION['user']['role'];




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php if ($role === 'admin'): ?>
        <form action="" method="post">
            <input type="hidden" name="token" value="<?= $_SESSION['token_titre_add']; ?>">
            <label for="title">Titre</label>
            <input type="text" name="title" id="title">
            <br>
            <label for="slug">Slug</label>
            <input type="text" name="slug" id="slug">
            <br>
            <label for="chanteur">Chanteur</label>
            <input type="text" name="chanteur" id="chanteur">
            <br>
            <button type="submit">Ajouter</button>
        </form>
    <?php endif; ?>
    <table border="1">
        <tbody>
            <?php

            foreach ($read as $key => $reads) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($reads["titre"]) . "</td>";
                echo "<td>" . htmlspecialchars($reads["chanteur"]) . "</td>";
                if ($role === 'admin') {
                    echo "<td>
                <form action='delete.php' method='post'>
                    <input type='hidden' name='musique_id' value='" . $reads['musique_id'] . "'>
                    <button type='submit' name='delete'>Supprimer</button>
                </form>
                </td>";
                    echo "<td>
            <form action='modif.php' method='post'>
                <input type='hidden' name='token_modif' value='" . $_SESSION['token_titre_mofid'] . "'>
                <input type='hidden' name='musique_id' value='" . $reads['musique_id'] . "'>
                <input type='text' name='title' value='" . htmlspecialchars($reads['titre']) . "' required>
                <input type='text' name='chanteur' value='" . htmlspecialchars($reads['chanteur']) . "' required>
                <button type='submit'>Modifier</button>
            </form>
        </td>";

                    echo "</tr>";
                }
            }
            ?>

        </tbody>
    </table>
</body>

</html>

<form action="" method="post">

</form>