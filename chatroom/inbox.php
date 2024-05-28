<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

$dsn = 'mysql:host=localhost;dbname=chat_system';
$username = 'root'; // Modify with your MySQL username
$password = ''; // Modify with your MySQL password
$db = new PDO($dsn, $username, $password);

// Fetch all conversations for the user
$stmt = $db->prepare("SELECT DISTINCT sender_id, receiver_id FROM messages WHERE sender_id = ? OR receiver_id = ?");
$stmt->execute([$userId, $userId]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$uniqueUsers = [];

foreach ($conversations as $conversation) {
    if ($conversation['sender_id'] == $userId) {
        $uniqueUsers[] = $conversation['receiver_id'];
    } else {
        $uniqueUsers[] = $conversation['sender_id'];
    }
}

$uniqueUsers = array_unique($uniqueUsers);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inbox</title>
</head>
<body>
    <h1>Inbox</h1>
    <ul>
        <?php foreach ($uniqueUsers as $otherUserId): ?>
            <li><a href="chatroom.php?user_id=<?php echo $otherUserId; ?>">User <?php echo $otherUserId; ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
