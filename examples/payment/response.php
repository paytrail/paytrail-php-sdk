<?php
/**
 * An example of a cart page.
 */
require 'vendor/autoload.php';

$client = new Client(
    375917,
    'SAIPPUAKAUPPIAS',
    'php-sdk-test'
);

try {
    $client->validateHmac($_GET, '', $_GET["signature"]);
} catch (HmacException $e) {
    die("Signature validation failed");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Paytrail Payment Service Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style> div {max-width: 1000px;}</style>
</head>
<body>
    <div class="container-fluid">
        <h1>Paytrail Payment Service Example</h1>
        <?php
        $status = $_GET["checkout-status"];
        if ($status === 'ok') {
            echo '<h2>Thank you for your purchase!</h2>';
            echo '<div class="alert alert-success">Payment status: ' . $status . '</div>';
        } else {
            echo '<h4>There was a problem processing your request</h4>';
            echo '<div class="alert alert-warning">Payment status: ' . $status . '</div>';
        }
        ?>
        <form method="post" action="index.php">
            <button type="submit" class="btn btn-primary">Continue shopping</button>
        </form>
    </div>
</body>
