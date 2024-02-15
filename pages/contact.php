<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nous contacter</title>
</head>
<body>
    <h1>Nous contacter</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Votre email :</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="sujet">Sujet :</label><br>
        <input type="text" id="sujet" name="sujet" required><br><br>
        <label for="message">Message :</label><br>
        <textarea id="message" name="message" rows="4" required></textarea><br><br>
        <input type="submit" value="Envoyer">
    </form>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dbphp";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $sujet = $_POST['sujet'];
        $message = $_POST['message'];

        $sql = "INSERT INTO demandes_contact (email, sujet, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $sujet, $message);

        if ($stmt->execute()) {
            echo "Votre message a été envoyé avec succès et enregistré.";
        } else {
            echo "Une erreur s'est produite lors de l'enregistrement de votre message.";
        }

        $conn->close();
    }
    ?>
</body>
</html>
