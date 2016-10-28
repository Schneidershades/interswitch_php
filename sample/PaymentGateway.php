<?php

/**
 * Description of PaymentGateway
 *
 * @author Abiola.Adebanjo
 */
require_once '../lib/Interswitch.php';
use Interswitch\Interswitch as InterswitchAPI;

const CLIENT_ID = "IKIA9614B82064D632E9B6418DF358A6A4AEA84D7218";
const CLIENT_SECRET = "XCTiBtLy1G9chAnyg0z3BcaFK4cVpwDg/GTw2EmjTZ8=";
const PURCHASE_RESOURCE_URL = "api/v2/purchases";
const HTTP_CODE = "HTTP_CODE";
const RESPONSE_BODY = "RESPONSE_BODY";
const CERTIFICATE_FILE_PATH = "../paymentgateway.crt";

function doPurchase($authData, $amount, $interswitchAPI) {

    $httpMethod = "POST";
    $transactionRef = generateRef();
    $customerId = "api-jam@interswitchgroup.com";
    $currency = "NGN";

    $data = array(
        "customerId" => $customerId,
        "amount" => $amount,
        "transactionRef" => $transactionRef,
        "currency" => $currency,
        "authData" => $authData
    );

    $request = json_encode($data);
    return $interswitchAPI->send(PURCHASE_RESOURCE_URL, $httpMethod, $request);
}

function doTransactionQuery($amount, $transactionRef, $interswitchAPI) {

    $httpMethod = "GET";
    $headers = array(
        'amount: ' . $amount,
        'transactionRef: ' . $transactionRef
    );

    return $interswitchAPI->send(PURCHASE_RESOURCE_URL, $httpMethod, null, $headers);
}

function generateRef() {
    $transRef = "ISW|API|JAM|" . mt_rand(0, 65535);
    return $transRef;
}

function test() {
    $interswitchAPI = new InterswitchAPI(CLIENT_ID, CLIENT_SECRET);
    $authData = $interswitchAPI->getAuthData(CERTIFICATE_FILE_PATH, '1', '6280511000000095', '5004', '111', '1111');

    $doPurchase = doPurchase($authData, '1000', $interswitchAPI);
    echo "=============================================Purchase=================================================================";
    echo "<br>";
    print_r($doPurchase);
    echo "<br>";

    $doTransactionQuery = doTransactionQuery('1000', 'ISW|API|JAM|62741', $interswitchAPI);
    echo "=============================================Transaction Query=================================================================";
    echo "<br>";
    print_r($doTransactionQuery);
}

test();
