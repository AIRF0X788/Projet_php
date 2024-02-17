<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Marker+Hatch&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fascinate+Inline&family=Rubik+Marker+Hatch&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/product.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Shade&family=Permanent+Marker&family=Whisper&display=swap" rel="stylesheet">
    <title>Navbar and Cards</title>
</head>

    <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="./catalogue.php">PHP</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto" style="font-size: 20px;"> 
                <li class="nav-item active">
                    <a class="nav-link" href="./catalogue.php">Accueil <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./profil.php">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./basket.php">Basket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./veste.php">Vestes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./pantalon.php">Pantalon</a>
                </li>
            </ul>
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<a href="' . $panier_url . '" class="btn btn-primary ml-2">Mon Panier <span class="badge badge-light">X</span></a>';
            }
            ?>
        </div>
    </div>
</nav>

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

