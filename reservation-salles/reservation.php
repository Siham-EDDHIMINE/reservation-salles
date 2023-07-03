<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=reservationsalles', 'root', 'Zen4321@');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    // Rediriger vers la page de connexion ou afficher un message d'erreur
    header('Location: connexion.php');
    exit;
}

// Vérifier si l'ID de la réservation est passé en paramètre
if (isset($_GET['id'])) {
    $reservationId = $_GET['id'];

    // Requête préparée pour récupérer les informations de la réservation
    $query = $bdd->prepare("SELECT * FROM reservations WHERE id = ?");
    $query->execute([$reservationId]);
    $reservation = $query->fetch(PDO::FETCH_ASSOC);

    // Vérifier si la réservation existe
    if ($reservation) {
        // Vérifier si l'utilisateur connecté est le créateur de la réservation
        if ($_SESSION['id'] == $reservation['id']) {
            // Afficher les détails de la réservation
            echo "Créateur : " . $reservation['id'] . "<br>";
            echo "Titre : " . $reservation['titre'] . "<br>";
            echo "Description : " . $reservation['description'] . "<br>";
            echo "Heure de début : " . $reservation['debut'] . "<br>";
            echo "Heure de fin : " . $reservation['fin'] . "<br>";
        } else {
            echo "Vous n'êtes pas autorisé à accéder à cette réservation.";
        }
    } else {
        header("Location: reservation-form.php");
    }
} else {
    echo "ID de réservation non spécifié.";
}
?>
