<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=reservationsalles', 'root', 'Zen4321@');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $date_debut = $_POST["date_debut"];
    $date_fin = $_POST["date_fin"];

    // Vérification des jours de la semaine (du lundi au vendredi)
    $jour_debut = date('N', strtotime($date_debut));
    $jour_fin = date('N', strtotime($date_fin));

    if ($jour_debut >= 1 && $jour_debut <= 5 && $jour_fin >= 1 && $jour_fin <= 5) {
        // Vérification des heures (8h - 19h)
        $heure_debut = date('H', strtotime($date_debut));
        $heure_fin = date('H', strtotime($date_fin));

        if ($heure_debut >= 8 && $heure_fin <= 19) {
            // Requête préparée pour insérer les informations dans la base de données
            $query = $bdd->prepare("INSERT INTO reservations (titre, description, debut, fin, id_utilisateur) VALUES (?, ?, ?, ?, ?)");
            $query->execute([$titre, $description, $date_debut, $date_fin, $_SESSION['id']]);

            echo "La réservation a été créée avec succès !";
        } else {
            echo "La réservation doit être faite entre 8h et 19h.";
        }
    } else {
        echo "La réservation ne peut être faite que du lundi au vendredi.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de réservation</title>
    <style>
        body {
            background-image: url("image02.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
    <h1>Créer une réservation</h1>
    <form method="post" action="reservation-form.php">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" required><br><br>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="date_debut">Date de début :</label>
        <input type="datetime-local" id="date_debut" name="date_debut" required><br><br>

        <label for="date_fin">Date de fin :</label>
        <input type="datetime-local" id="date_fin" name="date_fin" required><br><br>

        <input type="submit" value="Créer la réservation">
    </form>
</body>
</html>
