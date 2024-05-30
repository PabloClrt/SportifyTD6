<?php
// Connexion à la base de données
$servername = "localhost";
$username = "Maxime";
$password = "max";
$dbname = "projet_piscine";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Vérifier si le créneau sélectionné a été envoyé
if (isset($_POST['selectedSlot'])) {
    $selectedSlot = $_POST['selectedSlot'];

    // Vérifier si le créneau est disponible
    $sqlCheck = "SELECT * FROM disponibilites WHERE debut = '$selectedSlot' AND disponible = 1";
    $resultCheck = $conn->query($sqlCheck);
    
    if ($resultCheck->num_rows > 0) {
        $row = $resultCheck->fetch_assoc();
        $coach_id = $row['coach_id'];

        // Insérer le rendez-vous dans la base de données
        $sqlInsert = "INSERT INTO rendezvous (coach_id, date_heure) VALUES ('$coach_id', '$selectedSlot')";
        if ($conn->query($sqlInsert) === TRUE) {
            // Mettre à jour la disponibilité du créneau
            $sqlUpdate = "UPDATE disponibilites SET disponible = 0 WHERE debut = '$selectedSlot'";
            $conn->query($sqlUpdate);

            echo "Rendez-vous créé avec succès";
        } else {
            echo "Erreur lors de la création du rendez-vous : " . $conn->error;
        }
    } else {
        echo "Erreur : Le créneau sélectionné n'est pas disponible.";
    }
} else {
    echo "Erreur : Aucun créneau n'a été sélectionné.";
}

$conn->close();
?>
