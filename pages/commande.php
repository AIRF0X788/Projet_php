<!DOCTYPE html>
<html>

<head>
    <title>Commande</title>
</head>

<body>
    <h1 class="commande-header">Adresse de Livraison</h1>

    <?php
    session_start();

    if (isset($_GET['id_panier'])) {
        $id_panier = $_GET['id_panier'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "dbphp";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        $sql = "SELECT produits.nom, produits.description, produits.image_url, commande_produits.prix FROM commande_produits
                     JOIN produits ON produits.id_produit = commande_produits.id_produit
                     WHERE commande_produits.id_commande = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_panier);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<div class="commande-container">';

        $total = 0;

        while ($row = $result->fetch_assoc()) {
            echo '<div class="commande-item">';
            echo '<img src="' . $row['image_url'] . '" alt="' . $row['nom'] . '" style="width: 100px; height: 100px;">';
            echo '<h2>' . $row['nom'] . '</h2>';
            echo '<p>' . $row['description'] . '</p>';
            echo '<p>Prix : $' . number_format($row['prix'], 2) . '</p>';
            echo '</div>';

            $total += $row['prix'];
        }

        echo '</div>';

        $conn->close();

        echo '<p class="total">Total : $' . number_format($total, 2) . '</p>';

        echo '<h2>Informations d\'adresse</h2>';
        echo '<form method="post" action="commande.php?id_panier=' . $id_panier . '">';
        echo 'Rue : <input type="text" name="adresse_rue" required><br>';
        echo 'Ville : <input type="text" name="ville" required><br>';
        echo 'État : <input type="text" name="etat" required><br>';
        echo 'Code Postal : <input type="text" name="code_postal" required><br>';
        echo '<input type="submit" name="confirmer_commande" value="Confirmer la Commande">';
        echo '</form>';

        if (isset($_POST['confirmer_commande'])) {
            $adresse_rue = $_POST['adresse_rue'];
            $ville = $_POST['ville'];
            $etat = $_POST['etat'];
            $code_postal = $_POST['code_postal'];

            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("La connexion à la base de données a échoué : " . $conn->connect_error);
            }

            $user_id = $_SESSION['user_id'];

            $sql = "INSERT INTO adresses (id_utilisateur, adresse_rue, ville, etat, code_postal) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $user_id, $adresse_rue, $ville, $etat, $code_postal);
            $stmt->execute();

            $sql = "INSERT INTO commandes (id_utilisateur, date_commande, prix, nom_utilisateur) VALUES (?, CURRENT_DATE(), ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ids", $user_id, $total, $_SESSION['username']);
            $stmt->execute();

            $conn->close();

            echo 'Commande confirmée! Elle sera Livrée chez vous dans les jours à venir ';
        }
    } else {
        echo 'ID de panier non spécifié.';
    }
    ?>

    <a class="btn" href="./catalogue.php">Retourner au Catalogue</a>
    <footer>
        © 2023 PHP Site Web
        <a href="contact.php" class="btn btn-primary">Nous contacter</a>
    </footer>
</body>

</html>