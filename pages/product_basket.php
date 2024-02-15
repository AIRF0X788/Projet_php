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