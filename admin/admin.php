<?php
session_start();

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

$dsn = 'mysql:host=localhost;dbname=sportify';
$username = 'root'; // Modifier avec votre nom d'utilisateur MySQL
$password = ''; // Modifier avec votre mot de passe MySQL
$db = new PDO($dsn, $username, $password);

// Recherche de tous les coaches
$stmt = $db->query("
    SELECT c.coach_id, CONCAT(u.first_name, ' ', u.last_name) AS coach_name, u.email
    FROM coaches c
    JOIN users u ON c.user_id = u.user_id
");
$coaches = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $db->query("SELECT id, name, type FROM gyms");
$gyms = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    // Recherche d'un coach par nom, prénom ou email
    $stmt = $db->prepare("
        SELECT c.coach_id, CONCAT(u.first_name, ' ', u.last_name) AS coach_name, c.specialty AS specialty, u.email
        FROM coaches c
        JOIN users u ON c.user_id = u.user_id
        WHERE u.first_name LIKE ? 
        OR u.last_name LIKE ? 
        OR u.email LIKE ?
        OR c.specialty LIKE ?
    ");

    $stmt->execute([$search, $search, $search, $search]);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // Recherche d'un gym par nom ou email
    $stmt = $db->prepare("
        SELECT id, name, type
        FROM gyms
        WHERE name LIKE ? 
        OR type LIKE ?
    ");
    $stmt->execute([$search, $search]);
    $searchResultsGym = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administration</title>
    <style>
        .actions {
            display: inline-block;
        }
    </style>
</head>
<body>
    <h1>Administration</h1>
    <a href="create_coach.php">Créer un nouveau coach</a>
    <form action="admin.php" method="GET">
        <input type="text" name="search" placeholder="Rechercher un coach...">
        <button type="submit">Rechercher</button>
        <button type="button" onclick="window.location.href='admin.php'">Effacer la recherche</button>
    </form>

    <h2>Liste des Coaches</h2>
    <ul>       
        <?php if (isset($searchResults)): ?>
            <?php foreach ($searchResults as $coach): ?>
                <li>
                    <a href="coach_profile.php?id=<?php echo $coach['coach_id']; ?>">
                        <?php echo htmlspecialchars($coach['coach_name']); ?> - <?php echo htmlspecialchars($coach['email']); ?> - <?php echo htmlspecialchars($coach["specialty"]) ?>
                    </a>
                    <div class="actions" style="display: flex; flex-direction: row;">
                        <a href="edit_coach.php?id=<?php echo $coach['coach_id']; ?>">Modifier</a>
                        <form action="delete_coach.php" method="POST">
                            <input type="hidden" name="coach_id" value="<?php echo $coach['coach_id']; ?>">
                            <button type="submit" value="delete">Supprimer</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <?php foreach ($coaches as $coach): ?>
                <li>
                        <a href="coach_profile.php?id=<?php echo $coach['coach_id']; ?>">
                            <?php echo htmlspecialchars($coach['coach_name']); ?> - <?php echo htmlspecialchars($coach['email']); ?> - <?php echo htmlspecialchars($coach["specialty"]) ?>
                        </a>
                    <div class="actions">
                        <a href="edit_coach.php?id=<?php echo $coach['coach_id']; ?>">Modifier</a>
                        <form action="delete_coach.php" method="POST">
                            <input type="hidden" name="coach_id" value="<?php echo $coach['coach_id']; ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <h2>Liste des Gyms</h2>
<ul>
    <?php if (isset($searchResultsGym) && !empty($searchResultsGym)): ?>
        <?php foreach ($searchResultsGym as $gym): ?>
            <li>
                <a href="gym_profile.php?id=<?php echo $gym['id']; ?>">
                    <?php echo htmlspecialchars($gym['name']); ?> - <?php echo htmlspecialchars($gym['type']); ?>
                </a>
                <div class="actions">
                    <a href="edit_gym.php?id=<?php echo $gym['id']; ?>">Modifier</a>
                    <form action="delete_gym.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $gym['id']; ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($gyms as $gym): ?>
            <li>
                <a href="gym_profile.php?id=<?php echo $gym['id']; ?>">
                    <?php echo htmlspecialchars($gym['name']); ?> - <?php echo htmlspecialchars($gym['type']); ?>
                </a>
                <div class="actions">
                    <a href="edit_gym.php?id=<?php echo $gym['id']; ?>">Modifier</a>
                    <form action="delete_gym.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $gym['id']; ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

</body>
</html>