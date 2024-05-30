<?php
$dsn = 'mysql:host=localhost;dbname=sportify';
$username = 'root';
$password = '';
$db = new PDO($dsn, $username, $password);

$stmt = $db->query("SELECT s.name AS sport_name, c.coach_id, CONCAT(u.first_name, ' ', u.last_name) AS coach_name, c.photo, c.office, c.available_days, c.cv FROM sports s JOIN coaches c ON s.coach_id = c.coach_id JOIN users u ON c.user_id = u.user_id WHERE s.category = 'Activités sportives'");
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Activités sportives</title>
</head>
<body>
    <h1>Activités sportives</h1>
    <ul>
        <?php foreach ($activities as $activity): ?>
            <li>
                <h2><?php echo htmlspecialchars($activity['sport_name']); ?></h2>
                <p><img src="<?php echo htmlspecialchars($activity['photo']); ?>" alt="Photo de <?php echo htmlspecialchars($activity['coach_name']); ?>" width="100"></p>
                <p>Coach: <a href="coach_profile.php?id=<?php echo $activity['coach_id']; ?>"><?php echo htmlspecialchars($activity['coach_name']); ?></a></p>
                <p>Bureau: <?php echo htmlspecialchars($activity['office']); ?></p>
                <p>Disponibilité: <?php echo htmlspecialchars($activity['available_days']); ?></p>
                <p><a href="<?php echo htmlspecialchars($activity['cv']); ?>" target="_blank">Voir le CV</a></p>
                <p><a href="message_coach.php?id=<?php echo $activity['coach_id']; ?>">Envoyer un message</a></p>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
