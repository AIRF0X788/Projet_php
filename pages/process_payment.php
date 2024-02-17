<?php

require __DIR__ . '/../vendor/autoload.php';

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

$clientId = 'AbaeH59hs8nIOe7i4gE8kMfEg_cWHO2mfKDLA8jnA-8yXuMU-N1aBgnDo0BRY6q4I_QEQK6O9gZDPJjm';
$clientSecret = 'ENeNXq0_hoNEMYyCXGG09kIu89pYgGrjhIDotGGZfj4LdiCrmE-FbOuZijOGxWVarNSWnUvlk-Yk71ol';

$apiContext = new ApiContext(
    new OAuthTokenCredential($clientId, $clientSecret)
);

// Définir le mode (sandbox pour les tests, production pour le mode réel)
$apiContext->setConfig(['mode' => 'sandbox']);

$totalPrice = $_POST['total_price'];

// Créer un paiement PayPal
$payer = new Payer();
$payer->setPaymentMethod('paypal');

$amount = new Amount();
$amount->setTotal($totalPrice);
$amount->setCurrency('USD');

$transaction = new Transaction();
$transaction->setAmount($amount);

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl('http://localhost/xampp/Projet_php-main/pages/success.php')
    ->setCancelUrl('http://localhost/xampp/Projet_php-main/pages/cancel.php');

$payment = new Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions([$transaction])
    ->setRedirectUrls($redirectUrls);

try {
    $payment->create($apiContext);

    // Rediriger vers l'URL d'approbation PayPal
    header('Location: ' . $payment->getApprovalLink());
    exit;
} catch (Exception $ex) {

    echo "Une erreur s'est produite lors de la création du paiement PayPal: " . $ex->getMessage();
    header("Location: cancel.php");
    exit;

}

?>
