<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "dbphp";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}


$sql = "SELECT code FROM codes_promo WHERE actif = 1 ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    $row = $result->fetch_assoc();
    $promo_code = $row["code"];

  
    echo $promo_code;
} else {
    
    echo "Aucun code promo actif disponible pour le moment.";
}


$conn->close();
?>
