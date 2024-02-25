<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "dbphp";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $panier_url = "./panier.php";
    $wish_url = "./wish.php";
} else {
    $panier_url = "./panier.php";
    $wish_url = "./wish.php";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Produit</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/page_product.css">
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
                    echo '<a href="' . $wish_url . '" class="btn btn-info ml-2">Wishlist <span class="badge badge-light"></span></a>';
                } else {
                    echo '<a href="' . $wish_url . '" class="btn btn-info ml-2">Wishlist</a>';
                }
                ?>
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<a href="' . $panier_url . '" class="btn btn-dark ml-2">Mon Panier <span class="badge badge-light"></span></a>';
                } else {
                    echo '<a href="' . $panier_url . '" class="btn btn-dark ml-2">Mon Panier</a>';
                }
                ?>
            </div>
        </div>
    </nav>
    <div class="mt-5 container">
        <?php
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];

            $stmt = $conn->prepare("SELECT * FROM pantalon WHERE id_pantalon = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                echo "<h1>" . $product['nom'] . "</h1>";
                echo "<img src='" . $product['image_url'] . "' alt='" . $product['nom'] . "'>";
                echo "<p>Description: " . $product['description'] . "</p>";
                echo "<p>Prix: $" . number_format($product['prix'], 2) . "</p>";

           
                echo "<div class='avis-section'>";
                echo "<h2>Avis</h2>";

              
                $avis_stmt = $conn->prepare("SELECT * FROM avis_pantalon WHERE id_produit = ?");
                $avis_stmt->bind_param("i", $product_id);
                $avis_stmt->execute();
                $avis_result = $avis_stmt->get_result();

                while ($avis = $avis_result->fetch_assoc()) {
                    echo "<p><strong>" . $avis['nom_utilisateur'] . " : </strong> " . $avis['commentaire'] . " (Note: " . $avis['note'] . "/5, Date: " . $avis['date_avis'] . ")</p>";
                }

               
                echo "<h3 class='mt-5'>Ajouter un avis</h3>";
                echo "<form action='ajouter_avis_pantalon.php' method='post'>";
                echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
                echo "<div class='form-group'>";
                echo "<label for='commentaire'>Commentaire:</label>";
                echo "<textarea class='form-control' name='commentaire' id='commentaire' rows='4' required></textarea>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<label for='note'>Note (sur 5):</label>";
                echo "<input type='number' class='form-control' name='note' id='note' min='1' max='5' required>";
                echo "</div>";
                echo "<button type='submit' class='btn btn-primary'>Ajouter l'avis</button>";
                echo "</form>";

                echo "</div>";
            } else {
                echo "Aucun produit trouvé avec cet ID.";
            }
        } else {
            echo "ID du produit non spécifié.";
        }

        $conn->close();
        ?>
    </div>
    <div class="mb-5"></div>
    <footer>
        © 2023 PHP Site Web
        <a href="contact.php" class="btn btn-primary">Nous contacter</a>
    </footer>

</body>

</html>
