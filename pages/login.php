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
    <link
        href="https://fonts.googleapis.com/css2?family=Fascinate+Inline&family=Rubik+Marker+Hatch&family=Sedgwick+Ave+Display&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/connecion.css">
    <link rel="stylesheet" href="../css/loading.css">
    <title>Login</title>
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

    <body>
        <div class="section">
            <div class="container">
                <div class="row full-height justify-content-center">
                    <div class="col-12 text-center align-self-center py-5">
                        <div class="section pb-5 pt-5 pt-sm-2 text-center">
                            <h6 class="mb-0 pb-3"><span>Se connecter </span><span>Créer un compte</span></h6>
                            <input class="checkbox" type="checkbox" id="reg-log" name="reg-log" />
                            <label for="reg-log"></label>
                            <div class="card-3d-wrap mx-auto">
                                <div class="card-3d-wrapper">
                                    <div class="card-front">
                                        <div class="center-wrap">
                                            <div class="section text-center">
                                                <h4 class="mb-4 pb-3">Se connecter</h4>
                                                <form method="post" action="./login.php">
                                                <div class="form-group">
                                                    <input type="text" name="login_username" class="form-style" for="login_username"
                                                        placeholder="Nom d'utilisateur" id="login_username"
                                                        autocomplete="off" required>
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" name="login_password" class="form-style" for="login_password"
                                                        placeholder="Mot de passe" id="login_password"
                                                        autocomplete="off" required>
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <button type="submit" name="login" class="btn mt-4">Se connecter</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-back">
                                        <div class="center-wrap">
                                            <div class="section text-center">
                                                <h4 class="mb-4 pb-3">Creer un compte</h4>
                                                <form method="post" action="./login.php">
                                                <div class="form-group">
                                                    <input type="text" name="username" class="form-style" for="username"
                                                        placeholder="Nom d'utilisateur" id="username" autocomplete="off"
                                                        required>
                                                    <i class="input-icon uil uil-user"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="email" name="email" class="form-style" for="email"
                                                        placeholder="Email" id="email" autocomplete="off" required>
                                                    <i class="input-icon uil uil-at"></i>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input type="password" name="password" class="form-style" for="password"
                                                        placeholder="Mot de passe" id="password" autocomplete="off"
                                                        required>
                                                    <i class="input-icon uil uil-lock-alt"></i>
                                                </div>
                                                <button type="submit" class="btn mt-4">Créer le compte</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            © 2023 PHP Site Web
        </footer>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "dbphp";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        $sql_check_admin = "SELECT id_utilisateur FROM utilisateurs WHERE nom_utilisateur = 'admin' AND est_admin = 1";
        $result_check_admin = $conn->query($sql_check_admin);

        if ($result_check_admin->num_rows === 0) {
            $admin_username = 'admin';
            $admin_email = 'admin@admin.com';
            $admin_password = password_hash('1234', PASSWORD_DEFAULT);

            $sql = "INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe, est_admin) VALUES (?, ?, ?, 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $admin_username, $admin_email, $admin_password);
            $stmt->execute();
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

                if (mail($to, $subject, $message, $headers)) {

                    echo "Compte supprimé";
                } else {

                    echo "le compte a pas été supprimé";
                }
            } elseif (isset($_POST['login'])) {
                $login_username = $_POST['login_username'];
                $login_password = $_POST['login_password'];

                if (!empty($login_username) && !empty($login_password)) {
                    $sql = "SELECT id_utilisateur, nom_utilisateur, mot_de_passe, est_admin FROM utilisateurs WHERE nom_utilisateur = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $login_username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    if ($row && password_verify($login_password, $row['mot_de_passe'])) {
                        $_SESSION['user_id'] = $row['id_utilisateur'];
                        $user_name = $row['nom_utilisateur'];
                        setcookie("user_name_cookie", $user_name, time() + 86400, "/");
                        if ($row['est_admin'] == 1) {
                            header('Location: admin.php');
                        } else {
                            $_SESSION['popup_shown'] = true;
                            header('Location: catalogue.php');
                        }
                    } else {
                        echo "Nom d'utilisateur ou mot de passe incorrect.";
                    }
                } else {
                    echo "Veuillez saisir le nom d'utilisateur et le mot de passe.";
                }
            } else {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                $activation_token = bin2hex(random_bytes(32));

                $sql = "INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe, activation_token) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $username, $email, $password, $activation_token);
                $stmt->execute();

                $user_id = $conn->insert_id;
                $_SESSION['user_id'] = $user_id;

                $activation_link = "http://localhost/xampp/vrai%20php/pages/activer_compte.php?token=" . $activation_token;

                $to = $email;
                $subject = "Bienvenue sur Projet-Php.com";
                $message = "Bonjour $username,\n\nMerci d'avoir créé un compte sur notre site. Cliquez sur le lien suivant pour activer votre compte :\n$activation_link\n\nCordialement";
                $headers = "From: ynovmailoff@gmail.com";

                if (mail($to, $subject, $message, $headers)) {

                    echo "Compte crée et mail envoyé";
                } else {

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
            $(document).ready(function () {
                $('.input-group-text').on('click', function () {
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