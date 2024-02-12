<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $produit_id = $_GET['id'];
    $nom_produit = $_GET['nom'];
    $description_produit = $_GET['description'];
    $prix_produit = $_GET['prix'];
    $image_url_produit = $_GET['image_url'];

    $produit = [
        'id' => $produit_id,
        'nom' => $nom_produit,
        'description' => $description_produit,
        'prix' => $prix_produit,
        'image_url' => $image_url_produit
    ];

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    $_SESSION['panier'][] = $produit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    $index = $_POST['supprimer'];
    if (isset($_SESSION['panier'][$index])) {
        unset($_SESSION['panier'][$index]);
        $_SESSION['panier'] = array_values($_SESSION['panier']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider_panier'])) {
    if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0) {
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $database = "dbphp";
        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO paniers (id_utilisateur, prix) VALUES (?, ?)");
        $stmt->bind_param("id", $user_id, $total);

        $total = 0;
        foreach ($_SESSION['panier'] as $product) {
            $total += $product['prix'];
        }

        $stmt->execute();
        $id_panier = $conn->insert_id;

        foreach ($_SESSION['panier'] as $product) {
            $id_produit = $product['id'];
            $stmt = $conn->prepare("INSERT INTO commande_produits (id_commande, id_produit, prix) VALUES (?, ?, ?)");
            $stmt->bind_param("id", $id_panier, $id_produit, $product['prix']);
            $stmt->execute();
        }

        $conn->close();
        $_SESSION['panier'] = [];

        header('Location: commande.php?id_panier=' . $id_panier);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mon Panier</title>
</head>
<body>
    <h1>Mon Panier</h1>

    <?php
    if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0) {
        echo '<table>';
        echo '<tr><th>Produit</th><th>Nom</th><th>Description</th><th>Prix</th><th>Action</th></tr>';

        $total = 0;

        foreach ($_SESSION['panier'] as $index => $product) {
            echo '<tr>';
            echo '<td><img src="' . $product['image_url'] . '" alt="' . $product['nom'] . '"></td>';
            echo '<td>' . $product['nom'] . '</td>';
            echo '<td>' . $product['description'] . '</td>';
            echo '<td>$' . number_format($product['prix'], 2) . '</td>';
            echo '<td>';
            echo '<form method="post" action="panier.php">';
            echo '<input type="hidden" name="supprimer" value="' . $index . '">';
            echo '<input type="submit" value="Supprimer">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
            $total += $product['prix'];
        }

        echo '</table>';

        echo '<p class="total">Total : $' . number_format($total, 2) . '</p>';

        echo '<div class="valider-panier">';
        echo '<form method="post" action="panier.php">';
        echo '<input type="submit" name="valider_panier" value="Valider mon panier">';
        echo '</form>';
        echo '</div>';
    } else {
        echo '<p class="empty-cart">Votre panier est vide.</p>';
    }
    ?>

    <footer>
        © 2023 TIBAY Site Web
    </footer>
</body>
</html>