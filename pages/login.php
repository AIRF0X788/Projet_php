<?php
ob_start();
session_start();
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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Marker+Hatch&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fascinate+Inline&family=Rubik+Marker+Hatch&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Shade&family=Permanent+Marker&family=Whisper&display=swap" rel="stylesheet">
    <title>Navbar and Cards</title>
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
                    <a class="nav-link" href="./deconnexion.php">Se Déconecter</a>
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
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '<a class="nav-link" href="deconnexion.php">Déconnexion</a>';
                    } else {
                        echo '<a class="nav-link" href="connexion.php">Se connecter</a>';
                    }
                    ?>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0 ml-auto">
                <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
            </form>
            <a href="./panier.php" class="btn btn-primary ml-2">Mon Panier <span class="badge badge-light"></span></a>
        </div>
    </nav>

    <body class="bg-light">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ajouter un utilisateur</h5>
                            <form method="post" action="./login.php">
                                <div class="form-group">
                                    <label for="username">Nom d'utilisateur :</label>
                                    <input type="text" id="username" name="username" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email :</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Mot de passe :</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Supprimer un utilisateur</h5>
                            <form method="post" action="./login.php">
                                <div class="form-group">
                                    <label for="email_to_delete">Email de l'utilisateur à supprimer :</label>
                                    <input type="email" id="email_to_delete" name="email_to_delete" class="form-control">
                                </div>
                                <button type="submit" name="delete_user" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Se connecter</h5>
                            <form method="post" action="./login.php">
                                <div class="form-group">
                                    <label for="login_username">Nom d'utilisateur :</label>
                                    <input type="text" id="login_username" name="login_username" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="login_password">Mot de passe :</label>
                                    <input type="password" id="login_password" name="login_password" class="form-control" required>
                                </div>
                                <button type="submit" name="login" class="btn btn-success">Se connecter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "dbphp";

            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("La connexion à la base de données a échoué : " . $conn->connect_error);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['delete_user'])) {
                    $email_to_delete = $_POST['email_to_delete'];

                    $sql = "DELETE FROM utilisateurs WHERE email = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $email_to_delete);
                    $stmt->execute();

                    $to = $email_to_delete;
                    $subject = "Goodbye";
                    $message = "Vous avez supprimé votre compte";
                    $headers = "From: ynovmailoff@gmail.com";

                    if(mail($to, $subject, $message, $headers)) {

                        echo "Compte supprimé";

                    }
                    else {

                        echo "le compte a pas été supprimé";
                        
                    }

                    // echo "Utilisateur supprimé avec succès!";
                } else if (isset($_POST['login'])) {
                    $login_username = $_POST['login_username'];
                    $login_password = $_POST['login_password'];

                    $sql = "SELECT id_utilisateur, mot_de_passe FROM utilisateurs WHERE nom_utilisateur = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $login_username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    if ($row && password_verify($login_password, $row['mot_de_passe'])) {
                        $_SESSION['user_id'] = $row['id_utilisateur'];
                        header('Location: catalogue.php');
                        exit;
                    } else {
                        echo "Nom d'utilisateur ou mot de passe incorrect.";
                    }
                } else {
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $sql = "INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $username, $email, $password);
                    $stmt->execute();

                    $user_id = $conn->insert_id;
                    $_SESSION['user_id'] = $user_id;

                    $to = $email;
                    $subject = "Welcome $username";
                    $message = "Merci d'avoir crée un compte sur notre site";
                    $headers = "From: ynovmailoff@gmail.com";

                    if(mail($to, $subject, $message, $headers)) {

                        echo "Compte crée et mail envoyé";

                    }
                    else {

                        echo "mail pas envoyé";
                        
                    }
                }
            }

            $conn->close();
            ob_end_flush();
            ?>

            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('.input-group-text').on('click', function() {
                        const passwordInput = $(this).closest('.input-group').find('input');
                        const icon = $(this).find('i');

                        if (passwordInput.attr('type') === 'password') {
                            passwordInput.attr('type', 'text');
                            icon.removeClass('fa-eye').addClass('fa-eye-slash');
                        } else {
                            passwordInput.attr('type', 'password');
                            icon.removeClass('fa-eye-slash').addClass('fa-eye');
                        }
                    });
                });
            </script>
    </body>
</html>