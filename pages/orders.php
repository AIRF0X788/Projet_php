<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_utilisateur = $_SESSION['user_id'];

$conn = new PDO("mysql:host=localhost;dbname=dbphp", "root", "");
$stmt = $conn->prepare("SELECT * FROM commandes WHERE id_utilisateur = :id_utilisateur");
$stmt->bindParam(':id_utilisateur', $id_utilisateur);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des commandes</title>
</head>

<body>

    <h1>Historique des commandes</h1>

    <table>
        <thead>
            <tr>
                <th>Date de commande</th>
                <th>Détails</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order) : ?>
                <tr>
                    <td><?php echo $order['date_commande']; ?></td>
                    <td><a href="order_details.php?id=<?php echo $order['id_commande']; ?>">Détails</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>
