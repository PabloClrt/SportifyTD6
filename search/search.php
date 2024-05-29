<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$dsn = 'mysql:host=localhost;dbname=sportify';
$username = 'root'; // Modifier avec votre nom d'utilisateur MySQL
$password = ''; // Modifier avec votre mot de passe MySQL
$db = new PDO($dsn, $username, $password);

$query = isset($_GET['query']) ? $_GET['query'] : '';

$searchSoundex = soundex($query);
$searchLike = '%' . $query . '%';

// Recherche de coaches
$stmt = $db->prepare("
    SELECT c.coach_id, CONCAT(u.first_name, ' ', u.last_name) AS coach_name, c.specialty, c.photo, c.bio
    FROM coaches c
    JOIN users u ON c.user_id = u.user_id
    WHERE SOUNDEX(CONCAT(u.first_name, ' ', u.last_name)) = ? 
    OR SOUNDEX(u.first_name) = ? 
    OR SOUNDEX(u.last_name) = ? 
    OR SOUNDEX(c.specialty) = ? 
    OR SOUNDEX(c.bio) = ?
    OR CONCAT(u.first_name, ' ', u.last_name) LIKE ?
    OR u.first_name LIKE ?
    OR u.last_name LIKE ?
    OR c.specialty LIKE ?
    OR c.bio LIKE ?
    LIMIT 10
");
$stmt->execute([$searchSoundex, $searchSoundex, $searchSoundex, $searchSoundex, $searchSoundex, $searchLike, $searchLike, $searchLike, $searchLike, $searchLike]);
$coaches = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recherche de salles de sport
$stmt = $db->prepare("
    SELECT id, name, type
    FROM gyms
    WHERE SOUNDEX(name) = ? 
    OR SOUNDEX(type) = ?
    OR name LIKE ?
    OR type LIKE ?
    LIMIT 10
");
$stmt->execute([$searchSoundex, $searchSoundex, $searchLike, $searchLike]);
$gyms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Résultats de recherche</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h2 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: #007BFF;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Résultats de recherche</h1>

    <h2>Coaches</h2>
    <?php if (count($coaches) > 0): ?>
        <ul>
            <?php foreach ($coaches as $coach): ?>
                <li>
                    <a href="coach.php?id=<?php echo $coach['coach_id']; ?>">
                        <?php echo htmlspecialchars($coach['coach_name']); ?> - <?php echo htmlspecialchars($coach['specialty']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun coach trouvé.</p>
    <?php endif; ?>

    <h2>Salles de sport</h2>
    <?php if (count($gyms) > 0): ?>
        <ul>
            <?php foreach ($gyms as $gym): ?>
                <li>
                    <a href="gym.php?id=<?php echo $gym['id']; ?>">
                        <?php echo htmlspecialchars($gym['name']); ?> - <?php echo htmlspecialchars($gym['type']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune salle de sport trouvée.</p>
    <?php endif; ?>
</body>
</html>
