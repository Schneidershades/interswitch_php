# interswitch_php
Interswitch PHP Library


## Installation

Install the latest version with

```bash
$ composer require Interswitch/Interswitch
```

## Basic Usage

```php
<?php

use Interswitch\Interswitch;

// Initialize Interswitch object
$CLIENT_ID = "IKIA9614B82064D632E9B6418DF358A6A4AEA84D7218";
$CLIENT_SECRET = "XCTiBtLy1G9chAnyg0z3BcaFK4cVpwDg/GTw2EmjTZ8=";
$interswitchAPI = new InterswitchAPI($CLIENT_ID, $CLIENT_SECRET);

// Create sensitive data
$pan = "6280511000000095";
$expiryDate = "5004";
$cvv = "111";
$pin = "1111";
$authData = $interswitchAPI->getAuthData($pan, $expiryDate, $cvv, $pin);

// Build request data
$transactionRef = "ISW|API|JAM|" . mt_rand(0, 65535);
$customerId = "api-jam@interswitchgroup.com";
$currency = "NGN";
$amount = "50000"; // Minor denomination

$data = array(
 "customerId" => $customerId,
 "amount" => $amount,
 "transactionRef" => $transactionRef,
 "currency" => $currency,
 "authData" => $authData
);
$request = json_encode($data);
    
// add records to the log
$response = $interswitchAPI->send("api/v2/purchases", "POST", $request);
$httpRespCode = $response["HTTP_CODE"];
$respBody = $response["RESPONSE_BODY"];
```


## Third Party Packages

- phpseclib
- JWT

## About

### Requirements

- Intersiwtch SDK works with PHP 5.0 or above.

### Author

Lekan Omotayo - <developer@interswitchgroup.com><br />
Abiola Adebanjo - <developer@interswitchgroup.com><br />

### License

Interswitch SDK is licensed under the ISC License



