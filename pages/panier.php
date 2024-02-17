<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>

<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dbphp";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Requête pour récupérer tous les produits du panier de l'utilisateur
    $sql = "SELECT * FROM panier_utilisateur WHERE id_utilisateur = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<h2>Mon Panier</h2>';

        while ($row = $result->fetch_assoc()) {
            // Affichez les détails du produit
            echo '<div>';
            echo '<img src="' . $row['image_url'] . '" alt="' . $row['nom_produit'] . '" style="width:100px; height:100px;">';
            echo '<p>Nom : ' . $row['nom_produit'] . '</p>';
            echo '<p>Description : ' . $row['description_produit'] . '</p>';
            echo '<p>Prix : $' . $row['prix_produit'] . '</p>';
            echo '<p>Quantité : ' . $row['quantite'] . '</p>';
            echo '</div>';
        }
    } else {
        echo 'Le panier est vide.';
    }

    $conn->close();
} else {
    echo 'Utilisateur non connecté.';
}
?>