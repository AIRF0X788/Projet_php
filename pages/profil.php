<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
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

    if (isset($_POST['exchange_points'])) {
        $recompense_id = isset($_POST['recompense_id']) ? $_POST['recompense_id'] : null;
        $points_necessaires = 100;
    
        $stmt_attribuer_recompense = null;
    
        if ($recompense_id !== null) {
            $sql_recompense = "SELECT points_necessaires FROM recompenses WHERE id_recompense = ?";
            $stmt_recompense = $conn->prepare($sql_recompense);
    
            if ($stmt_recompense) {
                $stmt_recompense->bind_param("i", $recompense_id);
                $stmt_recompense->execute();
                $result_recompense = $stmt_recompense->get_result();
    
                if ($result_recompense->num_rows > 0) {
                    $row_recompense = $result_recompense->fetch_assoc();
                    $points_necessaires = $row_recompense['points_necessaires'];
                } else {
                    echo "Informations sur la récompense non trouvées.";
                }
    
                // Vérifiez si l'utilisateur a suffisamment de points
                if ($points_fidelite >= $points_necessaires) {
                    $sql_update_points = "UPDATE utilisateurs SET points_fidelite = points_fidelite - ? WHERE id_utilisateur = ?";
                    $stmt_update_points = $conn->prepare($sql_update_points);
    
                    if ($stmt_update_points) {
                        $stmt_update_points->bind_param("ii", $points_necessaires, $user_id);
    
                        if ($stmt_update_points->execute()) {
                            // Récupérer les informations sur la récompense
                            $sql_recompense_info = "SELECT nom, description FROM recompenses WHERE id_recompense = ?";
                            $stmt_recompense_info = $conn->prepare($sql_recompense_info);
    
                            if ($stmt_recompense_info) {
                                $stmt_recompense_info->bind_param("i", $recompense_id);
                                $stmt_recompense_info->execute();
                                $result_recompense_info = $stmt_recompense_info->get_result();
    
                                if ($result_recompense_info->num_rows > 0) {
                                    $row_recompense_info = $result_recompense_info->fetch_assoc();
                                    $nom_recompense = $row_recompense_info['nom'];
                                    $description_recompense = $row_recompense_info['description'];
    
                                    // Attribuer la récompense à l'utilisateur
                                    $sql_attribuer_recompense = "INSERT INTO recompenses_utilisateurs (id_utilisateur, id_recompense, date_attribution) VALUES (?, ?, NOW())";
                                    $stmt_attribuer_recompense = $conn->prepare($sql_attribuer_recompense);
    
                                    if ($stmt_attribuer_recompense) {
                                        $stmt_attribuer_recompense->bind_param("ii", $user_id, $recompense_id);
    
                                        if ($stmt_attribuer_recompense->execute()) {
                                            echo "Échange réussi. Vous avez obtenu la récompense : $nom_recompense - $description_recompense!";
                                        } else {
                                            echo "Erreur lors de l'attribution de la récompense : " . $stmt_attribuer_recompense->error;
                                        }
                                    } else {
                                        echo "Erreur lors de la préparation de la requête d'attribution de récompense : " . $conn->error;
                                    }
                                } else {
                                    echo "Informations sur la récompense non trouvées.";
                                }
                            } else {
                                echo "Erreur lors de la préparation de la requête d'informations sur la récompense : " . $conn->error;
                            }
                        } else {
                            echo "Erreur lors de la mise à jour des points : " . $stmt_update_points->error;
                        }
                    } else {
                        echo "Erreur lors de la préparation de la requête de mise à jour des points : " . $conn->error;
                    }
                } else {
                    echo "Points insuffisants pour effectuer l'échange.";
                }
            } else {
                echo "Erreur lors de la préparation de la requête de récupération des points nécessaires : " . $conn->error;
            }
        } else {
            echo "Avertissement : Aucun identifiant de récompense fourni.";
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

$sql_points = "SELECT points_fidelite FROM utilisateurs WHERE id_utilisateur = ?";
$stmt_points = $conn->prepare($sql_points);
$stmt_points->bind_param("i", $user_id);
$stmt_points->execute();
$result_points = $stmt_points->get_result();

if ($result_points->num_rows > 0) {
    $row_points = $result_points->fetch_assoc();
    $points_fidelite = $row_points['points_fidelite'];
} else {
    $points_fidelite = 10;
}

// Récupérer les récompenses disponibles
$sql_recompenses = "SELECT id_recompense, nom, description, points_necessaires FROM recompenses";
$result_recompenses = $conn->query($sql_recompenses);


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
    <link rel="stylesheet" href="../css/loading.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Shade&family=Permanent+Marker&family=Whisper&display=swap" rel="stylesheet">
    <title>Profil</title>
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
            <a href="#" class="btn btn-primary ml-2">Mon Panier <span class="badge badge-light"></span></a>
        </div>
    </nav>
    <div class="container mt-3">
        <h2>Profil de <?php echo $nom_utilisateur; ?></h2>
        <p><strong>ID Utilisateur:</strong> <?php echo $user_id; ?></p>
        <p><strong>Nom d'utilisateur:</strong> <?php echo $nom_utilisateur; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p><br><br>

        <div class="mb-3">
            <button class="btn btn-primary" onclick="showForm('usernameForm')">Changer le nom d'utilisateur</button>
            <button class="btn btn-primary" onclick="showForm('emailForm')">Changer l'adresse e-mail</button>
            <button class="btn btn-primary" onclick="showForm('passwordForm')">Changer le mot de passe</button>
        </div>

        <p><strong>Points de fidélité :</strong> <?php echo $points_fidelite; ?> points</p>

        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <label for="recompense_id">Échanger des points fidélité contre une récompense :</label>
            <select name="recompense_id" id="recompense_id">
                <?php
                while ($row_recompense = $result_recompenses->fetch_assoc()) {
                    echo "<option value='" . $row_recompense['id_recompense'] . "'>" . $row_recompense['nom'] . " - " . $row_recompense['points_necessaires'] . " points</option>";
                }
                ?>
            </select>
            <button type="submit" name="exchange_points" class="btn btn-primary">Échanger des points</button>
        </form>
        
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" id="usernameForm" style="display:none;">
            <label for="new_username">Changer le nom d'utilisateur:</label>
            <input type="text" name="new_username" id="new_username" required>
            <button type="submit" name="change_username" class="btn btn-primary">Changer le nom d'utilisateur</button>
        </form>
        <?php if (!empty($success_username)) {
            echo "<p class='text-success'>$success_username</p>";
        } ?>

        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" id="emailForm" style="display:none;">
            <label for="new_email">Changer l'adresse e-mail:</label>
            <input type="email" name="new_email" id="new_email" required>
            <button type="submit" name="change_email" class="btn btn-primary">Changer l'adresse e-mail</button>
        </form>
        <?php if (!empty($success_email)) {
            echo "<p class='text-success'>$success_email</p>";
        } ?>

        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" id="passwordForm" style="display:none;">
            <label for="new_password">Changer le mot de passe:</label>
            <input type="password" name="new_password" id="new_password" required>
            <button type="submit" name="change_password" class="btn btn-primary">Changer le mot de passe</button>
        </form>
        <?php if (!empty($success_password)) {
            echo "<p class='text-success'>$success_password</p>";
        } ?>
<a href="orders.php" class="btn btn-primary">Historique des commandes</a>

    </div>


    <footer>
        © 2023 PHP Site Web
        <a href="contact.php" class="btn btn-primary">Nous contacter</a>
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