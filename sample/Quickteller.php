<?php

require_once '../lib/Interswitch.php';

use Interswitch\Interswitch as InterswitchAPI;

const CLIENT_ID = "IKIAF6C068791F465D2A2AA1A3FE88343B9951BAC9C3";
        const CLIENT_SECRET = "FTbMeBD7MtkGBQJw1XoM74NaikuPL13Sxko1zb0DMjI=";
        const GET_BILLERS_RESOURCE_URL = "api/v1/quickteller/billers";
        const GET_CATEGORIES_RESOURCE_URL = "api/v1/quickteller/categorys";
        const INQUIRY_RESOURCE_URL = "api/v1/quickteller/transactions/inquirys";
        const TRANSACTION_RESOURCE_URL = "api/v1/quickteller/transactions";
        const HTTP_CODE = "HTTP_CODE";
        const RESPONSE_BODY = "RESPONSE_BODY";
        const CERTIFICATE_FILE_PATH = "../paymentgateway.crt";

function getQuicktellerBillers($interswitchAPI) {
    $httpMethod = "GET";
    $terminalId = "3IWP0001";

    $headers = array(
        'terminalId: ' . $terminalId
    );

    return $interswitchAPI->send(GET_BILLERS_RESOURCE_URL, $httpMethod, null, $headers);
}

function getQuicktellerBillersCategories($interswitchAPI) {
    $httpMethod = "GET";
    $terminalId = "3IWP0001";

    $headers = array(
        'terminalId: ' . $terminalId
    );

    return $interswitchAPI->send(GET_CATEGORIES_RESOURCE_URL, $httpMethod, null, $headers);
}

function doTransactionInquiry($interswitchAPI) {

    $httpMethod = "POST";
    $requestReference = generateRef();
    $customerId = "api-jam@interswitchgroup.com";
    $paymentCode = "70001";
    $terminalId = "3IWP0001";

    $headers = array(
        'terminalId: ' . $terminalId
    );

    $data = array(
        "customerId" => $customerId,
        "requestReference" => $requestReference,
        "customerId" => $customerId,
        "paymentCode" => $paymentCode
    );

    $request = json_encode($data);
    return $interswitchAPI->send(INQUIRY_RESOURCE_URL, $httpMethod, $request, $headers);
}

function doTransaction($interswitchAPI) {

    $httpMethod = "POST";
    $amount = 100;
    $bankCbnCode = "058";
    $msisdn = "07032479501";
    $transactionRef = "IWP|T|Web|3IWP0001|QTFT|261016185145|00000041";
    $terminalId = "3IWP0001";

    $secure = $interswitchAPI->getSecureData(CERTIFICATE_FILE_PATH, '6280511000000095', '5004', '111', '1111');
    $secureData = $secure["SECURE"];
    $pinData = $secure["PINBLOCK"];

    $headers = array(
        'terminalId: ' . $terminalId
    );

    $data = array(
        "amount" => $amount,
        "bankCbnCode" => $bankCbnCode,
        "msisdn" => $msisdn,
        "transactionRef" => $transactionRef,
        "secureData" => $secureData,
        "pinData" => $pinData
    );

    $request = json_encode($data);
    return $interswitchAPI->send(TRANSACTION_RESOURCE_URL, $httpMethod, $request, $headers);
}

function generateRef() {
    $transRef = "ISW|API|JAM|" . mt_rand(0, 65535);
    return $transRef;
}

function test() {
    $interswitchAPI = new InterswitchAPI(CLIENT_ID, CLIENT_SECRET);

    $billers = getQuicktellerBillers($interswitchAPI);
    echo "=============================================Quickteller Billers=================================================================";
    echo "<br>";
    print_r($billers);
    echo "<br>";
    
    $categories = getQuicktellerBillersCategories($interswitchAPI);
    echo "=============================================Quickteller Categories=================================================================";
    echo "<br>";
    print_r($categories);
    echo "<br>";
    
    $inquiry = doTransactionInquiry($interswitchAPI);
    echo "=============================================Quickteller Inquiry=================================================================";
    echo "<br>";
    print_r($inquiry);
    echo "<br>";

    $transaction = doTransaction($interswitchAPI);
    echo "=============================================Quickteller Transaction=================================================================";
    echo "<br>";
    print_r($transaction);
    echo "<br>";
}

test();
