<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: b.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "dbphp";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

$success_username = $success_email = $success_password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['change_username'])) {
        $new_username = $_POST['new_username'];
        $sql = "UPDATE utilisateurs SET nom_utilisateur = ? WHERE id_utilisateur = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_username, $user_id);
        if ($stmt->execute()) {
            $success_username = "Nom d'utilisateur mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du nom d'utilisateur : " . $stmt->error;
        }
    }

    if (isset($_POST['change_email'])) {
        $new_email = $_POST['new_email'];
        $sql = "UPDATE utilisateurs SET email = ? WHERE id_utilisateur = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_email, $user_id);
        if ($stmt->execute()) {
            $success_email = "Adresse e-mail mise à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour de l'adresse e-mail : " . $stmt->error;
        }
    }

    if (isset($_POST['change_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $sql = "UPDATE utilisateurs SET mot_de_passe = ? WHERE id_utilisateur = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_password, $user_id);
        if ($stmt->execute()) {
            $success_password = "Mot de passe mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du mot de passe : " . $stmt->error;
        }
    }
}

$sql = "SELECT id_utilisateur, nom_utilisateur, email FROM utilisateurs WHERE id_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id_utilisateur'];
    $nom_utilisateur = $row['nom_utilisateur'];
    $email = $row['email'];
} else {
    echo "Aucun résultat trouvé pour cet utilisateur.";
}

$conn->close();
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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Shade&family=Permanent+Marker&family=Whisper&display=swap" rel="stylesheet">
    <title>Profil</title>
</head>
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
                    <a class="nav-link" href="./pantalon.php">Déconnexion</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0 ml-auto">
                <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
            </form>
            <a href="#" class="btn btn-primary ml-2">Mon Panier <span class="badge badge-light"></span></a>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Profil de <?php echo $nom_utilisateur; ?></h2>
        <p><strong>ID Utilisateur:</strong> <?php echo $user_id; ?></p>
        <p><strong>Nom d'utilisateur:</strong> <?php echo $nom_utilisateur; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p><br><br>

        <div class="mb-4">
            <button class="btn btn-primary" onclick="showForm('usernameForm')">Changer le nom d'utilisateur</button>
            <button class="btn btn-primary" onclick="showForm('emailForm')">Changer l'adresse e-mail</button>
            <button class="btn btn-primary" onclick="showForm('passwordForm')">Changer le mot de passe</button>
        </div>

        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" id="usernameForm" style="display:none;">
            <label for="new_username">Changer le nom d'utilisateur:</label>
            <input type="text" name="new_username" id="new_username" required>
            <button type="submit" name="change_username" class="btn btn-primary">Changer le nom d'utilisateur</button>
        </form>
        <?php if (!empty($success_username)) { echo "<p class='text-success'>$success_username</p>"; } ?>

        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" id="emailForm" style="display:none;">
            <label for="new_email">Changer l'adresse e-mail:</label>
            <input type="email" name="new_email" id="new_email" required>
            <button type="submit" name="change_email" class="btn btn-primary">Changer l'adresse e-mail</button>
        </form>
        <?php if (!empty($success_email)) { echo "<p class='text-success'>$success_email</p>"; } ?>

        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" id="passwordForm" style="display:none;">
            <label for="new_password">Changer le mot de passe:</label>
            <input type="password" name="new_password" id="new_password" required>
            <button type="submit" name="change_password" class="btn btn-primary">Changer le mot de passe</button>
        </form>
        <?php if (!empty($success_password)) { echo "<p class='text-success'>$success_password</p>"; } ?>
    </div>


    <footer>
        © 2023 Baayvin Site Web
    </footer>

    <script>
        function showForm(formId) {
            document.getElementById('usernameForm').style.display = 'none';
            document.getElementById('emailForm').style.display = 'none';
            document.getElementById('passwordForm').style.display = 'none';

            document.getElementById(formId).style.display = 'block';
        }
    </script>
</body>
</html>
