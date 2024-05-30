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

// Requête SQL pour récupérer les disponibilités des coachs
$sql = "SELECT * FROM disponibilites";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Convertir le résultat en format JSON et l'afficher
    $disponibilites = array();
    while($row = $result->fetch_assoc()) {
        $disponibilites[] = $row;
    }
    echo json_encode($disponibilites);
} else {
    echo "Aucune disponibilité trouvée";
}
$conn->close();
?>
