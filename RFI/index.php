<?php
session_start();

// Générer et stocker le jeton CSRF dans la session
if (!isset($_SESSION['csrf_jeton'])) {
    $_SESSION['csrf_jeton'] = bin2hex(random_bytes(32));
}

if(isset($_POST['signin'])){
    // Vérifier si le jeton CSRF est présent dans la requête
    if (!isset($_POST['csrf_jeton']) || $_POST['csrf_jeton'] !== $_SESSION['csrf_jeton']) {
        die("Erreur de sécurité : Jeton CSRF invalide.");
    }


    
    $pdo = new PDO("mysql:host=localhost;dbname=db_secu", 'root', '');
   // Htmlspecialchars sert a la sécuriter en enlevant les caracteres spéciaux
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    //Requête préparé pour protéger des injections SQL
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username=:username AND password=:password");
    $stmt->execute(array(':username' => $username, ':password' => $password));
    $result = $stmt->fetch();
    
    if ($result){
        echo 'connexion réussie';
        exit();
    } else {
        echo 'utilisateur non reconnu';
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" type="text/css" rel="stylesheet">
    <title>Exercice login</title>
</head>
<body>
    <div id="formulaire">
        <form method="POST">
            <h1>Connection</h1>
            <label>Identifiant</label>
            <br>
            <!-- required des deux champs pour valider augmente tjr la securiterss -->
            <input type="text" name= "username" required></input>
            <br>
            <label>Mot de passe</label>
            <br>
            <input type="password" name="password" required></input>
            <br>
            <!-- Champ caché pour le jeton CSRF -->
            <input type="hidden" name="csrf_jeton" value="<?php echo $_SESSION['csrf_jeton']; ?>">
            <input type="submit" value="Se connecter" name="signin"/>
        </form>
    </div>
</body>
</html>