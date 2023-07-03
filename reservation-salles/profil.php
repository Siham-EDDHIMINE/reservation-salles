<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=reservationsalles', 'root', 'Zen4321@');

if (isset($_GET['id']) and $_GET['id'] > 0) {
    $getid = intval($_GET['id']);
    $requser = $bdd->prepare('SELECT * FROM utilisateurs WHERE id = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();
}



if (isset($_SESSION['id'])) {
    $requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();
    if (isset($_POST['newlogin']) and !empty($_POST['newlogin']) and $_POST['newlogin'] != $user['login']) {
        $newlogin = htmlspecialchars($_POST['newlogin']);
        $insertlogin = $bdd->prepare("UPDATE utilisateurs SET login = ? WHERE id = ?");
        $insertlogin->execute(array($newlogin, $_SESSION['id']));
        header('Location: profil.php?id=' . $_SESSION['id']);
    }
    
    if (isset($_POST['newpassword']) and !empty($_POST['newpassword']) and isset($_POST['newpassword2']) and !empty($_POST['newpassword2'])) {
        $password = $_POST['newpassword'];
        $password2 = $_POST['newpassword2'];
        if ($password == $password2) {
            $insertmdp = $bdd->prepare("UPDATE utilisateurs SET password = ? WHERE id = ?");
            $insertmdp->execute(array($password, $_SESSION['id']));
            header('Location: profil.php?id=' . $_SESSION['id']);
        } else {
            $msg = "Vos deux mdp ne correspondent pas !";
        }
    }
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <link href="style.css" rel="stylesheet" type="text/css">
        <title>Profil</title>
        <style>
        body {
            background-image: url("image13.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }
    </style>
    </head>

    <body>

        <header>
            <nav id="navTop">
                <ul id="listeNavTop">
                    <section class="lien">
                        <a href="deconnexion.php">
                            <li>Déconnexion</li>
                        </a>
                    </section>
                    <section class="lien">
                        <a href="reservation.php">
                            <li>Reservation</li>
                        </a>
                    </section>
                    <section class="lien">
                        <a href="reservation-form.php">
                            <li>Reservation-form</li>
                        </a>
                    </section>
                </ul>
            </nav>
        </header>

        <main>
            <div align="center">
                <h2>Bienvenue <?php echo $userinfo['login']; ?></h2>
                <br>
                <form method="post" action="profil.php">
                    <table>
                        <tr>
                            <td align="right">
                                <label for="login"> Pseudo :</label>
                            </td>
                            <td>
                                <input type="text" name="newlogin" placeholder="login" value="<?php echo $user['login']; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label for="password"> Mot de passe :</label>
                            </td>
                            <td>
                                <input type="password" name="newpassword" placeholder="Mot de passe" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <label for="motdepasse2"> Confirmation mot de passe :</label>
                            </td>
                            <td>
                                <input type="password" name="newpassword2" placeholder="Confirmation du mot de passe" />
                            </td>
                        </tr>
                    </table> <br>
                    <input type="submit" value="Mettre à jour mon profil !" />
                </form>
            </div>

        </main>

    </body>

    </html>
<?php
} else {
    header("Location: connexion.php");
}
?>