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
$receiverId = $_GET['user_id'];
$stmt = $db->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)");
$stmt->execute([$userId, $receiverId, $receiverId, $userId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chatroom</title>
    <style>
        .sent {
    text-align: right;
    color: #0066ff; /* Couleur pour les messages envoyés */
}

.received {
    text-align: left;
    color: #009900; /* Couleur pour les messages reçus */
}
    </style>
</head>
<body>
    <h1>Welcome, User <?php echo htmlspecialchars($userId); ?></h1>
    <div id="chat">
        <?php foreach ($messages as $message): ?>
            <div class="<?php echo $message['sender_id'] == $userId ? 'sent' : 'received'; ?>">
                <?php echo htmlspecialchars($message['message']); ?>
            </div>
        <?php endforeach; ?>
    </div>
    <input type="text" id="message" placeholder="Type your message here...">
    <input type="hidden" id="sender_id" value="<?php echo htmlspecialchars($userId); ?>">
    <input type="hidden" id="receiver_id" value="<?php echo htmlspecialchars($receiverId); ?>"> <!-- Example receiver ID -->
    <input type="submit" value="Envoyer">
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
        messageDiv.classList.add("received")
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
            sentMessageDiv.classList.add("sent")
            chat.appendChild(sentMessageDiv);
            messageInput.value = '';
        }
    });
</script>

</body>
</html>
