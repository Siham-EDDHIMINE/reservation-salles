<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=reservationsalles', 'root', 'Zen4321@');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    // Rediriger vers la page de connexion ou afficher un message d'erreur
    header('Location: connexion.php');
    exit;
}

// Récupérer la date du lundi de la semaine en cours
$dateLundi = date('Y-m-d', strtotime('monday this week'));

// Calculer les dates des autres jours de la semaine
$datesSemaine = array();
for ($i = 0; $i < 7; $i++) {
    $date = date('Y-m-d', strtotime($dateLundi . " +$i day"));
    $datesSemaine[] = $date;
}

// Requête pour récupérer les réservations de la semaine en cours
$query = $bdd->prepare("SELECT * FROM reservations WHERE debut >= ? AND fin < ?");
$query->execute([$dateLundi, $datesSemaine[4]]); // Utiliser $datesSemaine[4] au lieu de $datesSemaine[6] pour exclure samedi et dimanche
$reservations = $query->fetchAll(PDO::FETCH_ASSOC);

// Tableau des jours de la semaine
$joursSemaine = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi');

// Affichage du planning
echo '<h1>Planning de la salle</h1>';
echo '<table>';
echo '<tr>';
echo '<th>Heures</th>';
foreach ($joursSemaine as $jour) {
    echo "<th>$jour</th>";
}
echo '</tr>';

// Boucle pour chaque heure
for ($heure = 8; $heure <= 19; $heure++) {
    echo '<tr>';
    echo "<td>$heure:00</td>";

    // Boucle pour chaque jour
    foreach ($datesSemaine as $index => $date) {
        // Exclure samedi et dimanche (index 5 et 6)
        if ($index >= 5) {
            break;
        }

        echo '<td>';

        // Crée un lien vers la page de réservation en passant l'ID en paramètre
        echo "<a href='reservation.php?id=" . getReservationId($reservations, $date, $heure) . "' style='text-decoration: none;'>";
        $reservationsHeure = array_filter($reservations, function ($reservation) use ($date, $heure) {
            return date('Y-m-d H', strtotime($reservation['debut'])) == $date . ' ' . $heure;
        });

        // Afficher le nombre de réservations pour cette heure et ce jour
        if (count($reservationsHeure) > 0) {
            echo '<p style="color: blue; ">' . count($reservationsHeure) . ' réservation(s)</p>';
        } else {
            echo '<p style="color: brown; text-decoration:none;">Aucune réservation</p>';
        }

        echo '</a>';
        echo '</td>';
    }

    echo '</tr>';
}

echo '</table>';

// Fonction pour obtenir l'ID de réservation pour un créneau spécifique
function getReservationId($reservations, $date, $heure)
{
    foreach ($reservations as $reservation) {
        $reservationDate = date('Y-m-d', strtotime($reservation['debut']));
        $reservationHour = date('H', strtotime($reservation['debut']));

        if ($reservationDate == $date && $reservationHour == $heure) {
            return $reservation['id'];
        }
    }

    return null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>planning</title>
    <style>
        body {
            background-image: url("image11.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
    
</body>
</html>