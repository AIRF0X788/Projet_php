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

    <div class="container">
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

        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];

            $stmt = $conn->prepare("SELECT * FROM basket WHERE id_basket = ?");
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

              
                $avis_stmt = $conn->prepare("SELECT * FROM avis_basket WHERE id_produit = ?");
                $avis_stmt->bind_param("i", $product_id);
                $avis_stmt->execute();
                $avis_result = $avis_stmt->get_result();

                while ($avis = $avis_result->fetch_assoc()) {
                    echo "<p><strong>" . $avis['nom_utilisateur'] . " : </strong> " . $avis['commentaire'] . " (Note: " . $avis['note'] . "/5, Date: " . $avis['date_avis'] . ")</p>";
                }

               
                echo "<h3>Ajouter un avis</h3>";
                echo "<form action='ajouter_avis_basket.php' method='post'>";
                echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
                echo "<label for='commentaire'>Commentaire:</label>";
                echo "<textarea name='commentaire' id='commentaire' rows='4' required></textarea>";
                echo "<label for='note'>Note (sur 5):</label>";
                echo "<input type='number' name='note' id='note' min='1' max='5' required>";
                echo "<button type='submit'>Ajouter l'avis</button>";
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

</body>

</html>