<?php

require '../vendor/autoload.php';

use PayPal\Api\Payment;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

$clientId = 'AbaeH59hs8nIOe7i4gE8kMfEg_cWHO2mfKDLA8jnA-8yXuMU-N1aBgnDo0BRY6q4I_QEQK6O9gZDPJjm';
$clientSecret = 'ENeNXq0_hoNEMYyCXGG09kIu89pYgGrjhIDotGGZfj4LdiCrmE-FbOuZijOGxWVarNSWnUvlk-Yk71ol';

$apiContext = new ApiContext(
    new OAuthTokenCredential($clientId, $clientSecret)
);

$apiContext->setConfig(['mode' => 'sandbox']);

$paymentId = $_GET['paymentId'];
$token = $_GET['token'];
$payerId = $_GET['PayerID'];

try {
    $payment = Payment::get($paymentId, $apiContext);

    echo 'Paiement réussi. Merci pour votre achat!';
} catch (Exception $ex) {
    echo "Une erreur s'est produite lors de la récupération des détails du paiement PayPal: " . $ex->getMessage();
    header("Location: cancel.php");
    exit;
}

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

<div id="loading">
        <img src="../image/Loading.gif" alt="Chargement en cours...">
        <p>Redirection en cours...</p>
    </div>

    <script>
        $(document).ready(function () {
            $('#loading').show();
        });

        setTimeout(function () {
            window.location.href = 'catalogue.php';
        }, 10000); 
    </script>
