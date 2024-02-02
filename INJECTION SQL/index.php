<?php
//pour l'injection SQL ont vient y placer le code 'OR 1=1 OR 1=' dans le champ login qui vas changer 
// la requete SQL en tjr oui et s'authentifier

if(isset($_POST['signin'])){
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
            <input type="submit" value="Se connecter" name="signin"/>
        </form>
    </div>
</body>
</html>