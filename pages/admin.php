<?php
ob_start();
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "dbphp";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $product_name = $_POST['product_name'];
        $product_description = $_POST['product_description'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_category = $_POST['product_category'];

        $selected_category = $_POST['selected_category'];

        $sql = "INSERT INTO $selected_category (nom, description, prix, image_url, category) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $product_name, $product_description, $product_price, $product_image, $product_category);
        $stmt->execute();
    } elseif (isset($_POST['delete_product'])) {
        $product_id = $_POST['product_id'];
        $selected_category = $_POST['selected_category'];

        $sql = "DELETE FROM $selected_category WHERE id_$selected_category = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        echo "Le produit avec l'ID $product_id a été supprimé avec succès de la catégorie $selected_category.";
    } elseif (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];

        $sql_delete_paniers = "DELETE FROM paniers WHERE id_utilisateur = ?";
        $stmt_delete_paniers = $conn->prepare($sql_delete_paniers);
        $stmt_delete_paniers->bind_param("i", $user_id);
        $stmt_delete_paniers->execute();

        $sql_delete_user = "DELETE FROM utilisateurs WHERE id_utilisateur = ?";
        $stmt_delete_user = $conn->prepare($sql_delete_user);
        $stmt_delete_user->bind_param("i", $user_id);
        $stmt_delete_user->execute();

        echo "L'utilisateur avec l'ID $user_id a été supprimé avec succès.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Marker+Hatch&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fascinate+Inline&family=Rubik+Marker+Hatch&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/product.css">
    <title>Admin Page</title>
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
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '<li class="nav-item"><a class="nav-link" href="./logout.php">Se déconnecter</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="./login.php">Se connecter</a></li>';
                    }

                    ?>
                </ul>
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
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Bienvenue sur la page d'administration</h2>
        <form method="post" action="admin.php">
            <h4>Choisir la page</h4>
            <div class="form-group">
                <select name="selected_category" class="form-control" required>
                    <option value="veste">Veste</option>
                    <option value="pantalon">Pantalon</option>
                    <option value="basket">Basket</option>
                </select>
            </div>
            <button type="submit" name="choose_category" class="btn btn-primary">Choisir</button>
        </form>
        <br>
        <br>
        <br>
        <form method="post" action="admin.php">
            <h4>Supprimer un compte utilisateur</h4>
            <div class="form-group">
                <label for="user_id">ID de l'utilisateur :</label>
                <input type="number" id="user_id" name="user_id" class="form-control" required>
            </div>
            <button type="submit" name="delete_user" class="btn btn-danger">Supprimer le compte utilisateur</button>
        </form>
        <hr>
        <?php if (isset($_POST['choose_category'])) : ?>
            <form method="post" action="admin.php">
                <h4>Ajouter/Supprimer un produit</h4>
                <input type="hidden" name="selected_category" value="<?= $_POST['selected_category'] ?>">
                <div class="form-group">
                    <label for="product_name">Nom du produit :</label>
                    <input type="text" id="product_name" name="product_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="product_description">Description :</label>
                    <textarea id="product_description" name="product_description" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="product_price">Prix :</label>
                    <input type="number" id="product_price" name="product_price" class="form-control" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="product_image">URL de l'image :</label>
                    <input type="text" id="product_image" name="product_image" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="product_category">Catégorie :</label>
                    <select id="product_category" name="product_category" class="form-control" required>
                        <option value="">Toutes les catégories</option>
                        <option value="Enfant">Enfant</option>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                    </select>
                </div>
                <button type="submit" name="add_product" class="btn btn-primary">Ajouter le produit</button>
            </form>
            
            <hr>
            <form method="post" action="admin.php">
                <h4>Supprimer un produit</h4>
                <input type="hidden" name="selected_category" value="<?= $_POST['selected_category'] ?>">
                <div class="form-group">
                    <label for="product_id">ID du produit :</label>
                    <input type="number" id="product_id" name="product_id" class="form-control" required>
                </div>
                <button type="submit" name="delete_product" class="btn btn-danger">Supprimer le produit</button>
            </form>
            
            <hr>
        <?php endif; ?>
        <h4>Demande de contact</h4>
        <a href="./demande_contact.php">Page de contact</a>
        <hr>
        
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$conn->close();
ob_end_flush();
?>