<?php
session_start();


if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $produit_id = $_GET['id'];


    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dbphp";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

   
    $table_name = 'basket';

  
    $sql = "SELECT * FROM $table_name WHERE id_basket = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $produit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produit = $result->fetch_assoc();

    if ($produit) {
        $sqlCheck = "SELECT * FROM wish_utilisateur WHERE id_utilisateur = ? AND id_produit = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $user_id, $produit_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $existingProduit = $resultCheck->fetch_assoc();

        if ($existingProduit) {
      
            $newPrix = $existingProduit['prix_produit'] + $produit['prix'];
            $sqlUpdate = "UPDATE wish_utilisateur SET prix_produit = ? WHERE id_utilisateur = ? AND id_produit = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("idii", $newQuantite, $newPrix, $user_id, $produit_id);
            $stmtUpdate->execute();
            header("Location: ./wish.php");
        } else {
        
            $sqlInsert = "INSERT INTO wish_utilisateur (id_utilisateur, id_produit, image_url, nom_produit, description_produit, prix_produit)
                          VALUES (?, ?, ?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("iisssd", $user_id, $produit_id, $produit['image_url'], $produit['nom'], $produit['description'], $produit['prix']);
            $stmtInsert->execute();
            header("Location: ./wish.php");
            exit();
        }
    } else {
        echo 'Le produit n\'existe pas.';
    }

 
    $conn->close();
} else {
    echo 'Erreur : Utilisateur non connecté ou ID du produit manquant.';
}

