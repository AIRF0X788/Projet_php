<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "dbphp";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

$sql = "SELECT * FROM demandes_contact ORDER BY date_demande DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Email</th><th>Sujet</th><th>Message</th><th>Date</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['sujet'] . "</td>";
        echo "<td>" . $row['message'] . "</td>";
        echo "<td>" . $row['date_demande'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Aucune demande de contact trouvée.";
}

$conn->close();
