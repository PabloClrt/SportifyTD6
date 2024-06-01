<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'];

$dsn = 'mysql:host=localhost;dbname=sportify';
$username = 'root'; // Modifier avec votre nom d'utilisateur MySQL
$password = ''; // Modifier avec votre mot de passe MySQL
$db = new PDO($dsn, $username, $password);

// Récupérer les informations de l'utilisateur
$stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Utilisateur non trouvé.";
    exit();
}

$coach = null;
if ($userRole === 'coach') {
    $stmt = $db->prepare("SELECT * FROM coaches WHERE user_id = ?");
    $stmt->execute([$userId]);
    $coach = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mon Compte</title>
</head>
<body>
    <h1>Mon Compte</h1>
    <form action="update_account.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
        <div>
            <label>Prénom:</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
        </div>
        <div>
            <label>Nom:</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
        </div>
        <div>
            <label>Adresse:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
        </div>
        <div>
            <label>Téléphone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
        </div>
        <?php if ($userRole === 'coach' && $coach): ?>
            <div>
                <label>Bio:</label>
                <textarea name="bio"><?php echo htmlspecialchars($coach['bio']); ?></textarea>
            </div>
            <div>
                <label>Photo:</label>
                <input type="file" name="photo">
                <?php if ($coach['photo']): ?>
                    <img src="<?php echo htmlspecialchars($coach['photo']); ?>" alt="Photo de profil" width="100">
                <?php endif; ?>
            </div>
            <div>
                <label>Jours Disponibles:</label>
                <textarea name="available_days"><?php echo htmlspecialchars($coach['available_days']); ?></textarea>
            </div>
            <div>
                <label>Bureau:</label>
                <input type="text" name="office" value="<?php echo htmlspecialchars($coach['office']); ?>">
            </div>
            <div>
                <label>CV:</label>
                <input type="file" name="cv">
                <?php if ($coach['cv']): ?>
                    <a href="<?php echo htmlspecialchars($coach['cv']); ?>">Voir le CV</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div>
            <button type="submit">Mettre à jour</button>
        </div>
    </form>

    <?php if ($userRole === 'admin'): ?>
        <a href="admin.php">Page d'administration</a>
    <?php endif; ?>
    <a href="/Sportify/chatroom/inbox.php">Vos messages</a>
    <a href="./logout.php">Se déconnecter</a>
</body>
</html>
