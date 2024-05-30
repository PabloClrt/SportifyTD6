<?php
$dsn = 'mysql:host=localhost;dbname=sportify';
$username = 'root'; // Modify with your MySQL username
$password = ''; // Modify with your MySQL password
$db = new PDO($dsn, $username, $password);

$gymId = isset($_GET['id']) ? $_GET['id'] : 0;

$stmt = $db->prepare("
    SELECT id, name, type
    FROM gyms
    WHERE id = ?
");
$stmt->execute([$gymId]);
$gym = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Salle de sport <?php echo htmlspecialchars($gym['name']); ?></title>
</head>
<body>
    <h1>Salle de sport <?php echo htmlspecialchars($gym['name']); ?></h1>
    <p>Type: <?php echo htmlspecialchars($gym['type']); ?></p>
</body>
</html>
