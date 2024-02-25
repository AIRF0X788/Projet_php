<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "dbphp";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $sujet = $_POST['sujet'];
    $message = $_POST['message'];

    $sql = "INSERT INTO demandes_contact (email, sujet, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $sujet, $message);

    if ($stmt->execute()) {
        echo "Votre message a été envoyé avec succès et enregistré.";
    } else {
        echo "Une erreur s'est produite lors de l'enregistrement de votre message.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/contact.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/loading.css">
    <title>Nous contacter</title>
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
    
    <div class="container mt-3">
        <?php if (!empty($success_message)) : ?>
            <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_message)) : ?>
            <div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
    </div>


    <div id="perspective">
        <div id="container">
            <div class="face front">
                <div id="white">
                    <h1>Contactez-nous!<br><span>--------</span></h1>
                </div>
            </div>
            <div class="face back">
                <div id="open"></div>
                <div id="folds"></div>
                <div class="button con">formulaire de contact</div>
                <div id="letter">
                    <hgroup>
                        <h1 id="info">Dérouler</h1>
                        <h2></h2>
                    </hgroup>
                    <p>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <span class="Email">
                            <input for="email" type="email" name="email" id="email" size="40" class="emailinput"
                                aria-required="true" aria-invalid="false" placeholder="Email" required>
                        </span>

                        <span class="Name">
                            <input for="sujet" type="text" name="sujet" id="sujet" value="" size="40" class="nameinput"
                                aria-required="true" aria-invalid="false" placeholder="Sujet" required>
                        </span>

                        <span class="Message">
                            <textarea for="message" name="message" id="message" cols="40" rows="10" aria-invalid="false"
                                placeholder="Message" required></textarea>
                        </span>

                        <input type="submit" value="Envoyer" class="button send">
                    </form>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div id="wrapper"></div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var C = $('#container'),
            A = $('#open'),
            L = $('#letter'),
            B = $('.button.con'),
            H = $('#letter hgroup h2'),
            F = $('.front'),
            W = $('#wrapper'),
            P = $('#perspective'),
            closed = true;
        $(function () {
            $("textarea").text("");
        });

        F.click(function () {
            C.css({
                'transition': 'all 1s',
                'transform': 'rotateY(180deg)',
            });
            A.css({
                'transition': 'all 1s .5s',
                'transform': 'rotateX(180deg)',
                'z-index': '0'
            });
            W.css({
                'visibility': 'visible'
            });
        });
        W.click(function () {
            var message = $.trim($('textarea').val());
            if (message.length > 0) {
                var r = confirm("Vous n’avez pas envoyé votre message, souhaitez-vous toujours fermer le formulaire?");
                if (r == false) {
                    return;
                }
                else {
                    document.getElementById("myform").reset();
                }
            }
            if (closed === false) {
                L.css({
                    'transition': 'all .7s',
                    'top': '3px',
                    'height': '200px'
                });
                P.css({
                    'transform': 'translateY(0px)'
                });
                F.css({
                    'transform': 'rotateZ(0deg)'
                });
                H.css({
                    'transition': 'all .5s',
                    'transform': 'rotateZ(0deg)'
                });
                C.css({
                    'transition': 'all 1.2s .95s'
                });
                A.css({
                    'transition': 'all 1.2s .7s'
                });
                H.css({
                    'transition': 'all .5s'
                });
                document.getElementById("info").innerHTML = "Dérouler";
                closed = true;
            }
            else {
                C.css({
                    'transition': 'all 1s .5s',
                });
                A.css({
                    'transition': 'all .5s',
                });
                closed = false;
            }
            C.css({
                'transform': 'rotateY(0deg) rotate(3deg)'
            });
            A.css({
                'transform': 'rotateX(0deg)',
                'z-index': '10'
            });
            W.css({
                'visibility': 'hidden'
            });
        });
        B.click(function () {

            L.css({
                'transition': 'all .5s 1s',
                'top': '-600px',
                'height': '550px'
            });
            P.css({
                'transition': 'all 1s',
                'transform': 'translateY(450px)'
            });
            H.css({
                'transition': 'all 1s',
                'transform': 'rotateZ(180deg)'
            });
            document.getElementById("info").innerHTML = "Contactez-nous";
        });

        $(document).ready(function () {
            $('#success-alert').hide();
            <?php if (!empty($success_message)) : ?>
                $('#success-alert').show();
            <?php endif; ?>
        });
    </script>
    <footer>
        © 2023 PHP Site Web
    </footer>
</body>

</html>