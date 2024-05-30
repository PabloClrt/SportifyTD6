<?php
$dsn = 'mysql:host=localhost;dbname=sportify';
$username = 'root'; // Modifier avec votre nom d'utilisateur MySQL
$password = ''; // Modifier avec votre mot de passe MySQL
$db = new PDO($dsn, $username, $password);

$stmt = $db->query("SELECT name, description, rules, schedule, responsible_contact FROM gym_services");
$gymServices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Salle de sport Omnes</title>
</head>
<body>
    <h1>Salle de sport Omnes</h1>
    <ul>
        <?php foreach ($gymServices as $service): ?>
            <li>
                <h2><?php echo htmlspecialchars($service['name']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                <p><strong>Règles :</strong> <?php echo nl2br(htmlspecialchars($service['rules'])); ?></p>
                <p><strong>Horaires :</strong> <?php echo nl2br(htmlspecialchars($service['schedule'])); ?></p>
                <p><strong>Contact responsable :</strong> <?php echo htmlspecialchars($service['responsible_contact']); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
