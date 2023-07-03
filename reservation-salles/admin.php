<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
            <nav id="navTop">
                <ul id="listeNavTop">


                    <section class="lien">
                        <a href="admin.php">
                            <li>Administrateurs</li>
                        </a>
                    </section>
                    <section class="lien">
                        <a href="deconnexion.php">
                            <li>Déconnexion</li>
                        </a>
                    </section>                    
                </ul>
            </nav>
        </header>

        <main>
        <?php 
session_start();
if (isset($_SESSION['mail']) && $_SESSION['mail'] == "admin@gmail.com") {
        $bdd = new PDO('mysql:host=localhost;dbname=reservationsalles', "root", "Zen4321@");

    $requete = $bdd->prepare("SELECT * from utilisateurs");
    $requete->execute();
    $users = $requete->fetchAll();
    
    echo "<table border='1'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>id</th>";
    echo "<th>login</th>";
    echo "<th>prenom</th>";
    echo "<th>nom</th>";
    echo "<th>password</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach($users as $key => $user){
        echo '<tr>';
        echo '<td>' . $user['id'] . '</td>';
        echo '<td>' . $user['login'] . '</td>';
        echo '<td>' . $user['prenom'] . '</td>';
        echo '<td>' . $user['nom'] . '</td>';
        echo '<td>' . $user['password'] . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
}else{
    header("Location: index.php");

}
?>
        </main>

</body>
</html>