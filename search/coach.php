<?php
$dsn = 'mysql:host=localhost;dbname=sportify';
$username = 'root'; // Modify with your MySQL username
$password = ''; // Modify with your MySQL password
$db = new PDO($dsn, $username, $password);

$coachId = isset($_GET['id']) ? $_GET['id'] : 0;

$stmt = $db->prepare("
    SELECT c.coach_id, u.name AS coach_name, c.specialty, c.photo, c.bio, c.available_days
    FROM coaches c
    JOIN users u ON c.user_id = u.id
    WHERE c.coach_id = ?
");
$stmt->execute([$coachId]);
$coach = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Coach <?php echo htmlspecialchars($coach['coach_name']); ?></title>
</head>
<body>
    <h1>Coach <?php echo htmlspecialchars($coach['coach_name']); ?></h1>
    <?php if ($coach['photo']): ?>
        <img src="<?php echo htmlspecialchars($coach['photo']); ?>" alt="Photo de <?php echo htmlspecialchars($coach['coach_name']); ?>">
    <?php endif; ?>
    <p>Specialty: <?php echo htmlspecialchars($coach['specialty']); ?></p>
    <p>Bio: <?php echo htmlspecialchars($coach['bio']); ?></p>
    <p>Available Days: <?php echo htmlspecialchars($coach['available_days']); ?></p>
</body>
</html>
