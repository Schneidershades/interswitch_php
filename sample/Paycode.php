<?php

require_once '../lib/Interswitch.php';

use Interswitch\Interswitch as InterswitchAPI;

const CLIENT_ID = "IKIAF6C068791F465D2A2AA1A3FE88343B9951BAC9C3";
        const CLIENT_SECRET = "FTbMeBD7MtkGBQJw1XoM74NaikuPL13Sxko1zb0DMjI=";
        const PURCHASE_RESOURCE_URL = "api/v1/pwm/subscribers/2348090673520/tokens";
        const HTTP_CODE = "HTTP_CODE";
        const RESPONSE_BODY = "RESPONSE_BODY";


function doRequestToken($amount, $transactionRef, $interswitchAPI) {
    $FEPI = 455;
    $httpMethod = "POST";
    $headers = array(
        'frontEndPartnerId: ' . $FEPI
    );
    $data = array(
        "ttid" => "123",
        "paymentMethodTypeCode" => "MMO",
        "paymentMethodCode" => "WEMA",
        "tokenLifeTimeInMinutes" => 10,
        "payWithMobileChannel" => "ATM",
        "transactionType" => "Withdrawal",
        "codeGenerationChannel" => "Mobile",
        "amount" => "500000",
        "accountNo" => "205015250201000100",
        "accountType" => "20",
        "autoEnroll" => "false",
        "oneTimePin" => "1234"
    );

    $request = json_encode($data);
    return $interswitchAPI->send(PURCHASE_RESOURCE_URL, $httpMethod, $request, $headers);
//    echo $request;
}

$interswitchAPI = new InterswitchAPI(CLIENT_ID, CLIENT_SECRET);

$paycode = doRequestToken('10000', '1234565', $interswitchAPI);


var_dump($paycode);

