<?php
session_start();
$dsn = 'mysql:host=localhost;dbname=sportify';
$username = 'root'; 
$password = '';
$db = new PDO($dsn, $username, $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Vérifiez si l'email existe déjà
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists) {
        $error = "Un utilisateur avec cet email existe déjà.";
    } else {
        // Enregistrer l'utilisateur sans la carte d'étudiant pour obtenir l'ID utilisateur
        $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password, role, address, phone) VALUES (?, ?, ?, ?, 'client', ?, ?)");
        if ($stmt->execute([$firstName, $lastName, $email, $password, $address, $phone])) {
            $userId = $db->lastInsertId();

            // Handling the uploaded student card image
            $targetDir = "uploads/";
            $studentCard = $targetDir . $userId . "." . strtolower(pathinfo($_FILES["student_card"]["name"], PATHINFO_EXTENSION));
            $uploadOk = 1;

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["student_card"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
                $error = "Le fichier n'est pas une image.";
            }

            // Check file size
            if ($_FILES["student_card"]["size"] > 500000) { // 500 KB
                $uploadOk = 0;
                $error = "Désolé, votre fichier est trop volumineux.";
            }

            // Allow certain file formats
            $imageFileType = strtolower(pathinfo($studentCard, PATHINFO_EXTENSION));
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $uploadOk = 0;
                $error = "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $error = "Désolé, votre fichier n'a pas été téléchargé.";
            } else {
                if (move_uploaded_file($_FILES["student_card"]["tmp_name"], $studentCard)) {
                    // File is uploaded successfully, now update user data with the student card path
                    $stmt = $db->prepare("UPDATE users SET student_card = ? WHERE user_id = ?");
                    if ($stmt->execute([$studentCard, $userId])) {
                        header('Location: login.php');
                        exit();
                    } else {
                        $error = "Une erreur s'est produite lors de la mise à jour de l'enregistrement. Veuillez réessayer.";
                    }
                } else {
                    $error = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
                }
            }
        } else {
            $error = "Une erreur s'est produite lors de l'enregistrement. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="register.php" enctype="multipart/form-data">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>
        <label for="student_card">Student Card (Image):</label>
        <input type="file" id="student_card" name="student_card" accept="image/*" required><br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
