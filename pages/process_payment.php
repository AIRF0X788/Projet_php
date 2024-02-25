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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$totalPrice = $_POST['total_price'];
$id_utilisateur = $_SESSION['user_id'];

try {
    $date_commande = date('Y-m-d H:i:s');
    $conn = new PDO("mysql:host=localhost;dbname=dbphp", "root", "");
    $stmt = $conn->prepare("INSERT INTO commandes (id_utilisateur, date_commande, total) VALUES (:id_utilisateur, :date_commande, :total)");
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);
    $stmt->bindParam(':date_commande', $date_commande);
    $stmt->bindParam(':total', $totalPrice);
    $stmt->execute();

    $points_gagnes = floor($totalPrice / 10);
    $stmt_update = $conn->prepare("UPDATE utilisateurs SET points_fidelite = points_fidelite + :points_gagnes WHERE id_utilisateur = :id_utilisateur");
    $stmt_update->bindParam(':points_gagnes', $points_gagnes);
    $stmt_update->bindParam(':id_utilisateur', $id_utilisateur);
    $stmt_update->execute();

    $payer = new Payer();
    $payer->setPaymentMethod('paypal');

    $amount = new Amount();
    $amount->setTotal($totalPrice);
    $amount->setCurrency('EUR');

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

    $payment->create($apiContext);

    header('Location: ' . $payment->getApprovalLink());
    exit;
} catch (Exception $ex) {
    echo "Une erreur s'est produite lors de la création du paiement PayPal: " . $ex->getMessage();
    header("Location: cancel.php");
    exit;
}
