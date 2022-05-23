<?php
/**
 * The Paytrail form view.
 *
 * The payment transaction is created on page load.
 *
 * The user selects the payment provider which takes the user to complete the payment transaction.
 */

require_once('examples/payment/Providers.php');
require_once('examples/payment/Payment.php');

$data = [
    'email' => filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING ),
    'firstName' => filter_input( INPUT_POST, 'first-name', FILTER_SANITIZE_STRING ),
    'lastName' => filter_input( INPUT_POST, 'last-name', FILTER_SANITIZE_STRING ),
    'phone' => (int) filter_input( INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT ),
    'amount' => (int) filter_input( INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT ),
    'address' => filter_input( INPUT_POST, 'address', FILTER_SANITIZE_STRING ),
    'postalCode' => filter_input( INPUT_POST, 'postal-code', FILTER_SANITIZE_STRING ),
    'city' => filter_input( INPUT_POST, 'city', FILTER_SANITIZE_STRING ),
    'country' => filter_input( INPUT_POST, 'country', FILTER_SANITIZE_STRING ),
    'county' => filter_input( INPUT_POST, 'county', FILTER_SANITIZE_STRING )
];

if ($data['county'] != '') {

    $payment = new Payment();

    $providers = new Providers();

    $paymentData = $payment->processPayment($data);

    if (isset($paymentData['error'])) {
        echo '<h3>An error has occurred: ' . $paymentData['error'] . '</h3>';
        die();
    }

    $paymentData = $paymentData['data'];

    $payProviders = $paymentData->getProviders();

    $arr = array();
    foreach ($payProviders as $key => $item) {
        $arr[$item->getGroup()][$key] = $item;
    }

    $groupData = $providers->getProviderGroupData($data['amount'] * 100, $data['country']);

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Paytrail Payment Service Example</title>
    </head>
    <body>
    <h1>Paytrail</h1>

        <fieldset>
            <legend>Select the payment provider</legend>
            <?php

            echo '<p>Go directly to <a href="' . $paymentData->getHref() . '" target="_blank">Paytrail Payment Service</a></p>';

            $terms_link = $groupData['terms'];
            echo '<div class="paytrail-terms-link">' . $terms_link . '</div>';
            echo '<div class="container-fluid ml-0">';

            foreach ($arr as $group){

                $id = reset($group)->getGroup();

                echo '<h5 class="text-capitalize mt-4">' . $id . '</h5>';
                echo '<div class="group-' . $id . ' row">';

                foreach ($group as $provider) {

                    echo "<div class='" . $provider->getName() . " border m-2' >";
                    echo "  <form action='" . $provider->getUrl() . "' method='POST'>";
                    foreach ($provider->getParameters() as $i => $param) {
                        $param = json_decode(json_encode($param), true);
                        echo "<input type='hidden' name='" . $param['name'] . "' value='" . $param['value'] . "' />";
                    }
                    echo "    <input type='image' src='" . $provider->getIcon() . "' alt='Submit' value='" . $provider->getName() . "' />";
                    echo "  </form>";
                    echo "</div>";

                }
                echo '</div>';
            }
            echo '</div>';

                ?>
        </fieldset>
    </body>
    </html>
<?php
} else {
    echo '<div class="alert alert-warning"> Please submit!</div>';
}
?>
