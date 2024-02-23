<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $connectButtonText = 'Se Déconnecter';
    $loginPage = './logout.php';
    $panier_url = "./panier.php";
} else {
    $connectButtonText = 'Se connecter';
    $loginPage = './login.php';
    $panier_url = "./panier.php";
}

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        if (isset($_SESSION['popup_shown']) && $_SESSION['popup_shown']) {
            if (isset($_COOKIE['user_name_cookie'])) {
                $user_name = $_COOKIE['user_name_cookie'];

                echo "Swal.fire({
                    title: 'Félicitations!',
                    text: 'Connexion réussie. Bienvenue sur notre site, $user_name !',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'GET',
                            url: './récupérer_code_promos.php', // Endpoint pour récupérer le code promo
                            success: function(response) { 
                                Swal.fire({
                                    title: 'Code Promo',
                                    html: '-10% sur ta prochaine commande !<br>Avec le code : ' + response,
                                    icon: 'info',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    setTimeout(function() {
                                        location.replace('" . $_SERVER['PHP_SELF'] . "');
                                    }, 2000);
                                });
                            }
                        });
                    }
                });";
            }

            $_SESSION['popup_shown'] = false;
        }
        ?>
    });
</script>

</head>

<body>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="./catalogue.php">PHP</a>
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
                <form class="form-inline my-2 my-lg-0 ml-auto" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Search" name="search_term">
                <input type="hidden" name="is_pantalon_search" value="1">
                <input type="hidden" name="is_veste_search" value="2">
                <input type="hidden" name="is_basket_search" value="3">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
            </form>


                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<a href="' . $panier_url . '" class="btn btn-primary ml-2">Mon Panier <span class="badge badge-light"></span></a>';
                } else {
                    echo '<a href="' . $panier_url . '" class="btn btn-primary ml-2">Mon Panier</a>';
                }
                ?>
        </nav>
        <h2 class="text-center">Les Nouveautés</h2>
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="category-filter">Filtrer par catégorie :</label>
            <select id="category-filter" name="category">
                <option value="">Toutes les catégories</option>
                <option value="Enfant">Enfant</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>
            <button type="submit">Filtrer</button>
        </form>
                
        <?php

        $filter_category = isset($_GET['category']) ? $_GET['category'] : '';
        $search_term = isset($_GET['search_term']) ? strtolower($_GET['search_term']) : '';
        $is_veste_search = isset($_GET['is_veste_search']) ? $_GET['is_veste_search'] : '';
        $is_pantalon_search = isset($_GET['is_pantalon_search']) ? $_GET['is_pantalon_search'] : '';
        $is_basket_search = isset($_GET['is_basket_search']) ? $_GET['is_basket_search'] : '';

      
        $sql = "SELECT id_produit, nom, description, prix, image_url, category FROM produits";

   
        if (!empty($filter_category)) {
            $sql .= " WHERE category = ?";
        }

  
        if ($is_veste_search == '2' && strpos($search_term, 've') !== false) {
            $sql = "SELECT id_veste, nom, description, prix, image_url, category FROM veste WHERE nom LIKE '%$search_term%'";
        } elseif ($is_pantalon_search == '1' && strpos($search_term, 'pa') !== false) {
            $sql = "SELECT id_pantalon, nom, description, prix, image_url, category FROM pantalon WHERE nom LIKE '%$search_term%'";
        } elseif ($is_basket_search == '3' && strpos($search_term, 'ba') !== false) {
            $sql = "SELECT id_basket, nom, description, prix, image_url, category FROM basket WHERE nom LIKE '%$search_term%'";
        }

        $stmt = $conn->prepare($sql);

      
        if (!empty($filter_category)) {
            $stmt->bind_param("s", $filter_category);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo '<div class="card">';
            echo '<img src="' . $row['image_url'] . '" alt="' . $row['nom'] . '" style="width:100%">';
            echo '<div class="container">';
            echo '<h4><b>' . $row['nom'] . '</b></h4>';
            echo '<p class="category">' . $row['category'] . '</p>';
            echo '<p>' . $row['description'] . '</p>';
            echo '<p>Prix : $' . number_format($row['prix'], 2) . '</p>';
        
            
            if (isset($row['id_pantalon'])) {
                echo '<a href="product_pantalon.php?id=' . $row['id_pantalon'] . '" class="btn btn-primary">Voir Détails</a>';
            } elseif (isset($row['id_veste'])) {
                echo '<a href="product_veste.php?id=' . $row['id_veste'] . '" class="btn btn-primary">Voir Détails</a>';
            } elseif (isset($row['id_basket'])) {
                echo '<a href="product_basket.php?id=' . $row['id_basket'] . '" class="btn btn-primary">Voir Détails</a>';
            } else {
                echo '<a href="product_catalogue.php?id=' . $row['id_produit'] . '" class="btn btn-primary">Voir Détails</a>';
            }


            if (isset($user_id)) {
                $sql_user = "SELECT statut FROM utilisateurs WHERE id_utilisateur = ?";
                $stmt_user = $conn->prepare($sql_user);
                $stmt_user->bind_param("i", $user_id);
                $stmt_user->execute();
                $result_user = $stmt_user->get_result();
                $user = $result_user->fetch_assoc();
        
                if ($user['statut'] == 'actif') {
                    $id_for_cart = isset($row['id_pantalon']) ? $row['id_pantalon'] : (isset($row['id_veste']) ? $row['id_veste'] : (isset($row['id_basket']) ? $row['id_basket'] : $row['id_produit']));
                    $add_to_cart_href = isset($row['id_pantalon']) ? 'ajouter_panier_pantalon.php' : (isset($row['id_veste']) ? 'ajouter_panier_veste.php' : (isset($row['id_basket']) ? 'ajouter_panier_basket.php' : 'ajouter_panier_catalogue.php'));
        
                    echo '<a href="' . $add_to_cart_href . '?id=' . $id_for_cart . '&user_id=' . $user_id . '" class="btn btn-success">Ajouter au Panier</a>';
                } else {
                    echo '<a href="#" class="btn btn-success">Votre compte n\'est pas vérifié pour ajouter au panier</a>';
                }
            } else {
                echo '<a href="./login.php" class="btn btn-success">Connexion pour Ajouter au Panier</a>';
            }
        
            echo '</div>';
            echo '</div>';
        }

        $stmt->close();
        $conn->close();
        ?>
        
        
      
    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <footer>
            © 2023 PHP Site Web
            <a href="contact.php" class="btn btn-primary">Nous contacter</a>
        </footer>
    </body>
    
    </html>