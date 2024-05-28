<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch previous messages from the database
$dsn = 'mysql:host=localhost;dbname=chat_system';
$username = 'root'; // Modify with your MySQL username
$password = ''; // Modify with your MySQL password
$db = new PDO($dsn, $username, $password);
$stmt = $db->prepare("SELECT * FROM messages WHERE sender_id = ? OR receiver_id = ?");
$stmt->execute([$userId, $userId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chatroom</title>
</head>
<body>
    <h1>Welcome, User <?php echo htmlspecialchars($userId); ?></h1>
    <div id="chat">
        <?php foreach ($messages as $index => $message): ?>
            <div id="message-<?php echo $index; ?>"><?php echo htmlspecialchars($message['message']); ?></div>
        <?php endforeach; ?>
    </div>
    <input type="text" id="message" placeholder="Type your message here...">
    <input type="hidden" id="sender_id" value="<?php echo htmlspecialchars($userId); ?>">
    <input type="hidden" id="receiver_id" value="1"> <!-- Example receiver ID -->
    <script>
    const conn = new WebSocket('ws://localhost:8081');
    const chat = document.getElementById('chat');
    const messageInput = document.getElementById('message');
    const senderId = document.getElementById('sender_id').value;
    const receiverId = document.getElementById('receiver_id').value;

    conn.onmessage = function(event) {
        const data = JSON.parse(event.data);
        const messageDiv = document.createElement('div');
        messageDiv.textContent = data.message;
        chat.appendChild(messageDiv);
    };

    messageInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            const message = messageInput.value;
            const data = JSON.stringify({
                sender_id: senderId,
                receiver_id: receiverId,
                message: message
            });
            conn.send(data);
            // Ajout du message à la boîte de dialogue côté client
            const sentMessageDiv = document.createElement('div');
            sentMessageDiv.textContent = message;
            sentMessageDiv.style.textAlign = 'right'; // Alignement du message à droite
            chat.appendChild(sentMessageDiv);
            messageInput.value = '';
        }
    });
</script>

</body>
</html>
