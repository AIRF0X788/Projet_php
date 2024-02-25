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

$message = '';

try {
    $payment = Payment::get($paymentId, $apiContext);

    $message = 'Paiement réussi. Merci pour votre achat!';
} catch (Exception $ex) {
    $message = "Une erreur s'est produite lors de la récupération des détails du paiement PayPal: " . $ex->getMessage();
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="font-weight-bold display-4"><?php echo $message; ?></h1>
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="mt-3">Redirection en cours...</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    setTimeout(function () {
        window.location.href = 'catalogue.php';
    }, 5000);
</script>

</body>
</html>
