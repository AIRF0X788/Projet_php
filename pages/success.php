<?php

require 'vendor/autoload.php';

use PayPal\Api\Payment;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

// Définir vos clés d'API PayPal
$clientId = 'AbaeH59hs8nIOe7i4gE8kMfEg_cWHO2mfKDLA8jnA-8yXuMU-N1aBgnDo0BRY6q4I_QEQK6O9gZDPJjm';
$clientSecret = 'ENeNXq0_hoNEMYyCXGG09kIu89pYgGrjhIDotGGZfj4LdiCrmE-FbOuZijOGxWVarNSWnUvlk-Yk71ol';

// Configurer le contexte API PayPal
$apiContext = new ApiContext(
    new OAuthTokenCredential($clientId, $clientSecret)
);

// Définir le mode (sandbox pour les tests, production pour le mode réel)
$apiContext->setConfig(['mode' => 'sandbox']);

// Récupérer l'ID de paiement depuis le paramètre GET
$paymentId = $_GET['paymentId'];
$token = $_GET['token'];
$payerId = $_GET['PayerID'];

// Récupérer les détails du paiement
try {
    $payment = Payment::get($paymentId, $apiContext);

    echo 'Paiement réussi. Merci pour votre achat!';
} catch (Exception $ex) {
    echo "Une erreur s'est produite lors de la récupération des détails du paiement PayPal: " . $ex->getMessage();
    header("Location: cancel.php");
    exit;
}

?>
