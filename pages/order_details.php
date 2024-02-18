<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_commande = $_GET['id'];

$conn = new PDO("mysql:host=localhost;dbname=dbphp", "root", "");
$stmt = $conn->prepare("SELECT * FROM commandes WHERE id_commande = :id_commande");
$stmt->bindParam(':id_commande', $id_commande);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la commande</title>
</head>

<body>

    <h1>Détails de la commande</h1>

    <h2>ID Commande: <?php echo $order['id_commande']; ?></h2>
    <p>Date de commande: <?php echo $order['date_commande']; ?></p>
    <p>Total: <?php echo $order['total']; ?></p>

</body>

</html>
