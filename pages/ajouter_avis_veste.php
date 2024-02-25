<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $commentaire = $_POST['commentaire'];
    $note = $_POST['note'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dbphp";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    $nom_utilisateur = "Utilisateur Anonyme";

    if (isset($_SESSION['user_id'])) {
        $id_utilisateur = $_SESSION['user_id'];

        $get_user_stmt = $conn->prepare("SELECT nom_utilisateur FROM utilisateurs WHERE id_utilisateur = ?");
        $get_user_stmt->bind_param("i", $id_utilisateur);
        $get_user_stmt->execute();
        $user_result = $get_user_stmt->get_result();

        if ($user_row = $user_result->fetch_assoc()) {
            $nom_utilisateur = $user_row['nom_utilisateur'];
        }
    }

    $existing_review_stmt = $conn->prepare("SELECT id_avis FROM avis_veste WHERE id_utilisateur = ? AND id_produit = ?");
    $existing_review_stmt->bind_param("ii", $id_utilisateur, $product_id);
    $existing_review_stmt->execute();
    $existing_review_result = $existing_review_stmt->get_result();

    if ($existing_review_result->num_rows > 0) {
        $update_review_stmt = $conn->prepare("UPDATE avis_veste SET commentaire = ?, note = ? WHERE id_utilisateur = ? AND id_produit = ?");
        $update_review_stmt->bind_param("sdsi", $commentaire, $note, $id_utilisateur, $product_id);

        if ($update_review_stmt->execute()) {
            echo "Avis mis à jour avec succès!";
        } else {
            echo "Erreur lors de la mise à jour de l'avis : " . $update_review_stmt->error;
        }
    } else {
        // Ajout de la date actuelle
        $date_avis = date('Y-m-d H:i:s');

        $insert_stmt = $conn->prepare("INSERT INTO avis_veste (id_utilisateur, id_produit, commentaire, note, nom_utilisateur, date_avis) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("iisdss", $id_utilisateur, $product_id, $commentaire, $note, $nom_utilisateur, $date_avis);

        if ($insert_stmt->execute()) {
            $update_average_rating_stmt = $conn->prepare("UPDATE veste SET note_moyenne = (SELECT AVG(note) FROM avis_veste WHERE id_produit = ?) WHERE id_veste = ?");
            $update_average_rating_stmt->bind_param("ii", $product_id, $product_id);

            if ($update_average_rating_stmt->execute()) {
                echo "Avis ajouté avec succès!";
            } else {
                echo "Erreur lors de la mise à jour de la note moyenne : " . $update_average_rating_stmt->error;
            }
        } else {
            echo "Erreur lors de l'ajout de l'avis : " . $insert_stmt->error;
        }
    }

    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirection en cours...</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    setTimeout(function () {
        window.location.href = 'catalogue.php';
    }, 1000);
</script>

</body>
</html>
