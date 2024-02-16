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

if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appliquer_code_promo'])) {
    $code_promo = $_POST['code_promo'];

    $sql = "SELECT valeur FROM codes_promo WHERE code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $code_promo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $reduction = $row['valeur'];

        $_SESSION['reduction'] = $reduction;
    } else {
        echo "Code promo invalide.";
    }
} else {
    unset($_SESSION['reduction']);
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
            $stmt->bind_param("idi", $id_panier, $id_produit, $product['prix']);
            $stmt->execute();
        }

        $stmt = $conn->prepare("INSERT INTO paniers (id_utilisateur, prix) VALUES (?, ?)");
        $stmt->bind_param("id", $user_id, $total_panier);

        $total_panier = 0;
        foreach ($_SESSION['panier'] as $product) {
            $total_panier += $product['prix'];
        }

        $stmt->execute();
        $id_panier = $conn->insert_id;


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
    <link rel="stylesheet" href="../css/panier.css">

</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">PHP</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
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
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<li class="nav-item"><a class="nav-link" href="./logout.php">Se Déconnecter</a></li>';
                } else {
                    echo '<li class="nav-item"><a class="nav-link" href="./login.php">Se Connecter</a></li>';
                }

                ?>
            </ul>
            <form class="form-inline my-2 my-lg-0 ml-auto">
                <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
            </form>
            
            <?php
            if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $sql_check_admin = "SELECT est_admin FROM utilisateurs WHERE id_utilisateur = ? AND est_admin = 1";
                $stmt_check_admin = $conn->prepare($sql_check_admin);
                $stmt_check_admin->bind_param("i", $user_id);
                $stmt_check_admin->execute();
                $result_check_admin = $stmt_check_admin->get_result();

                if ($result_check_admin->num_rows > 0) {
                    echo '<a href="./admin.php" class="btn btn-success ml-2">Admin</a>';
                }
            }
            ?>
    </nav>
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

    <form method="post" action="panier.php">
        <input type="text" name="code_promo" placeholder="Entrez votre code promo">
        <input type="submit" name="appliquer_code_promo" value="Appliquer">
    </form>

    <?php
    if (isset($_SESSION['reduction'])) {
        $reductionPercentage = $_SESSION['reduction'];
        $reductionAmount = $total * $reductionPercentage;
        $total -= $reductionAmount;
        echo '<p class="total">Total (avec réduction) : $' . number_format($total, 2) . '</p>';
    }    
    ?>

    <footer>
        © 2023 PHP Site Web
        <a href="contact.php" class="btn btn-primary">Nous contacter</a>
    </footer>
</body>

</html>