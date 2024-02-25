<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $panier_url = "./panier.php";
} else {
    $panier_url = "./panier.php";
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$total_price = 0;

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
    ?>

    <!DOCTYPE html>
    <html>

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
        <link
            href="https://fonts.googleapis.com/css2?family=Fascinate+Inline&family=Rubik+Marker+Hatch&family=Sedgwick+Ave+Display&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="../css/product.css">
        <link rel="stylesheet" href="../css/loading.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Bungee+Shade&family=Permanent+Marker&family=Whisper&display=swap"
            rel="stylesheet">
        <script src="https://www.paypal.com/sdk/js?client-id=YOUR_CLIENT_ID&currency=USD"></script>
        <title>Ma wishlist</title>

    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="./catalogue.php">PHP</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                        echo '<a href="' . $panier_url . '" class="btn btn-primary ml-2">Mon Panier <span class="badge badge-light"></span></a>';
                    } else {
                        echo '<a href="' . $panier_url . '" class="btn btn-primary ml-2">Mon Panier</a>';
                    }
                    ?>
                </div>
            </div>
        </nav>

        <footer>
            © 2023 PHP Site Web
            <a href="contact.php" class="btn btn-primary">Nous contacter</a>
        </footer>

        <div class="container">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['delete_product'])) {
                    $product_id = $_POST['product_id'];

                    $sql_delete = "DELETE FROM wish_utilisateur WHERE id_produit = ? AND id_utilisateur = ?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    $stmt_delete->bind_param("ii", $product_id, $user_id);
                    $stmt_delete->execute();

                    header("Location: wish.php");
                    exit;
                }
            }

            $sql = "SELECT * FROM wish_utilisateur WHERE id_utilisateur = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo '<h2>Ma wishlist</h2>';
                echo '<table class="product-table">';
                echo '<tr>';
                echo '<th>Image</th>';
                echo '<th>Nom</th>';
                echo '<th>Description</th>';
                echo '<th>Prix</th>';
                echo '<th>Action</th>';
                echo '</tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td><img src="' . $row['image_url'] . '" alt="' . $row['nom_produit'] . '" class="product-img"></td>';
                    echo '<td>' . $row['nom_produit'] . '</td>';
                    echo '<td>' . $row['description_produit'] . '</td>';
                    echo '<td>' . $row['prix_produit'] . '</td>';
                    echo '<td>';
                    echo '<form method="post" action="wish.php">';
                    echo '<input type="hidden" name="product_id" value="' . $row['id_produit'] . '">';
                    echo '<input type="submit" name="delete_product" class="btn btn-danger" value="Supprimer">';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';

                    $total_price += $row['prix_produit'];
                }

                echo '</table>';

            } else {
                echo 'La wishlist est vide.';
            }

            $conn->close();
} else {
    echo 'Utilisateur non connecté.';
}

?>
    </div>

</body>

</html>