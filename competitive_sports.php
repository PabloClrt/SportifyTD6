<?php
$dsn = 'mysql:host=localhost;dbname=sportify';
$username = 'root'; // Modifier avec votre nom d'utilisateur MySQL
$password = ''; // Modifier avec votre mot de passe MySQL
$db = new PDO($dsn, $username, $password);

$stmt = $db->query("SELECT s.name AS sport_name, c.coach_id, CONCAT(u.first_name, ' ', u.last_name) AS coach_name, c.photo, c.office, c.available_days, c.cv FROM sports s JOIN coaches c ON s.coach_id = c.coach_id JOIN users u ON c.user_id = u.user_id WHERE s.category = 'Sports de compétition'");
$competitiveSports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Les Sports de compétition</title>
</head>
<body>
    <h1>Les Sports de compétition</h1>
    <ul>
        <?php foreach ($competitiveSports as $sport): ?>
            <li>
                <h2><?php echo htmlspecialchars($sport['sport_name']); ?></h2>
                <p><img src="<?php echo htmlspecialchars($sport['photo']); ?>" alt="Photo de <?php echo htmlspecialchars($sport['coach_name']); ?>" width="100"></p>
                <p>Coach: <a href="coach_profile.php?id=<?php echo $sport['coach_id']; ?>"><?php echo htmlspecialchars($sport['coach_name']); ?></a></p>
                <p>Bureau: <?php echo htmlspecialchars($sport['office']); ?></p>
                <p>Disponibilité: <?php echo htmlspecialchars($sport['available_days']); ?></p>
                <p><a href="<?php echo htmlspecialchars($sport['cv']); ?>" target="_blank">Voir le CV</a></p>
                <p><a href="message_coach.php?id=<?php echo $sport['coach_id']; ?>">Envoyer un message</a></p>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
