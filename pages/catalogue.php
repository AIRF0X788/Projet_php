<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $connectButtonText = 'Se déconnecter';
    $loginPage = './logout.php'; 
} else {
    $connectButtonText = 'Se connecter';
    $loginPage = './login.php';
}
?>
<!DOCTYPE html>
<html lang="en">
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
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Baayvin</a>
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
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $loginPage; ?>"><?php echo $connectButtonText; ?></a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0 ml-auto">
                <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
            </form>
            <a href="./login.php" class="btn btn-primary ml-2">Mon Panier <span class="badge badge-light"></span></a>
        </div>
    </nav>
    <h2 class="text-center">Les Nouveautés</h2>

    <?php
    $servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $database = "dbphp";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id_produit, nom, description, prix, image_url FROM produits");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<div class="card">';
        echo '<img src="' . $row['image_url'] . '" alt="' . $row['nom'] . '" style="width:100%">';
        echo '<div class="container">';
        echo '<h4><b>' . $row['nom'] . '</b></h4>';
        echo '<p>' . $row['description'] . '</p>';
        echo '<p>Prix : $' . number_format($row['prix'], 2) . '</p>';
        echo '<a href="panier.php?id=' . $row['id_produit'] . '&nom=' . $row['nom'] . '&description=' . $row['description'] . '&prix=' . $row['prix'] . '&image_url=' . $row['image_url'];
        if (isset($user_id)) {
            echo '&user_id=' . $user_id;
        }
        echo '" class="btn btn-success">Ajouter au Panier</a>';
        echo '</div>';
        echo '</div>';
    }

    $conn->close();
    ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <footer>
        © 2023 Baayvin Site Web
    </footer>
</body>
</html>