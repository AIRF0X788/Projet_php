<?php
session_start();

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

$apiContext->setConfig(['mode' => 'sandbox']);

$totalPrice = $_POST['total_price'];
$id_utilisateur = $_SESSION['user_id'];

$payer = new Payer();
$payer->setPaymentMethod('paypal');

$amount = new Amount();
$amount->setTotal($totalPrice);
$amount->setCurrency('USD');

$transaction = new Transaction();
$transaction->setAmount($amount);

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl('http://localhost/xampp/vrai%20php/pages/success.php')
    ->setCancelUrl('http://localhost/xampp/vrai%20php/pages/cancel.php');

$payment = new Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions([$transaction])
    ->setRedirectUrls($redirectUrls);

try {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "dbphp";
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données: " . $conn->connect_error);
    }

    $date_commande = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO commandes (id_utilisateur, date_commande, prix) VALUES (?, ?, ?)");

    if (!$stmt) {
        die("Erreur de préparation de la requête SQL : " . $conn->error);
    }

    $stmt->bind_param('iss', $id_utilisateur, $date_commande, $totalPrice);

    if (!$stmt->execute()) {
        die("Erreur lors de l'exécution de la requête SQL : " . $stmt->error);
    }

    $payment->create($apiContext);
    header('Location: ' . $payment->getApprovalLink());
    exit;
} catch (Exception $ex) {
    echo "Une erreur s'est produite lors de la création du paiement PayPal: " . $ex->getMessage();
    header("Location: cancel.php");
    exit;
}
