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
    <link href="https://fonts.googleapis.com/css2?family=Fascinate+Inline&family=Rubik+Marker+Hatch&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/product.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Shade&family=Permanent+Marker&family=Whisper&display=swap" rel="stylesheet">
    <title>Mon panier</title>

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
                if (isset($user_id)) {
                    echo '<a href="ajouter_wishlist.php?produitId= ?" class="btn btn-danger ml-2">Wishlist</a>';
                }
                ?>

                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<a href="' . $panier_url . '" class="btn btn-primary ml-2">Mon Panier <span class="badge badge-light">X</span></a>';
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
if (isset($_POST['produitId'])) {
    $produitId = $_POST['produitId'];

    // Vérifie si l'ID du produit est spécifié dans l'URL
    if (isset($_GET['produitId'])) {
    $produitId = $_GET['produitId'];

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

        // Vérifie si le produit est déjà dans la wishlist de l'utilisateur
        $sql_check = "SELECT * FROM wishlist WHERE id_utilisateur = ? AND id_produit = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $user_id, $produitId);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Le produit est déjà dans la wishlist, donc le supprimer
            $sql_remove = "DELETE FROM wishlist WHERE id_utilisateur = ? AND id_produit = ?";
            $stmt_remove = $conn->prepare($sql_remove);
            $stmt_remove->bind_param("ii", $user_id, $produitId);
            $stmt_remove->execute();
            echo "Produit retiré de la wishlist";
        } else {
            // Le produit n'est pas dans la wishlist, donc l'ajouter
            $sql_add = "INSERT INTO wishlist (id_utilisateur, id_produit) VALUES (?, ?)";
            $stmt_add = $conn->prepare($sql_add);
            $stmt_add->bind_param("ii", $user_id, $produitId);
            $stmt_add->execute();
            echo "Produit ajouté à la wishlist";
        }
    } else {
        echo "Veuillez vous connecter pour ajouter des produits à la wishlist";
    }

    } else {
        echo "Erreur : ID du produit non spécifié";
    }

} else {
    echo "Erreur : ID du produit non spécifié";
}
?>
</div>
</body>

</html>
