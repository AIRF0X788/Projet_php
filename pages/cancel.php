<?php
// Gérer l'annulation du paiement
echo 'Paiement annulé. Vous serez redirigé vers la page d\'accueil dans quelques secondes...';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirection en cours...</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <style>
        #loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            text-align: center;
            padding-top: 20%;
        }

        #loading img {
            width: 50px;
        }
    </style>
</head>

<body>
    <div id="loading">
        <img src="Loading.gif" alt="Chargement en cours...">
        <p>Redirection en cours...</p>
    </div>

    <script>
        $(document).ready(function () {
            $('#loading').show();
        });

        setTimeout(function () {
            window.location.href = 'catalogue.php';
        }, 10000); // Délai de 10 secondes
    </script>
</body>

</html>
