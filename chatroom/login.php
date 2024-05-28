<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $_SESSION['user_id'] = $userId;
    header('Location: chatroom.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form method="POST" action="login.php">
        <label for="user_id">User ID:</label>
        <input type="text" name="user_id" id="user_id" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
