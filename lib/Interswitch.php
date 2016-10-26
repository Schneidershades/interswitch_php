<?php

/**
 * Description of InterswitchAuth
 *
 * @author Abiola.Adebanjo
 */

namespace Interswitch;

include_once 'Util\Utils.php';
include_once 'Util\Constants.php';

class Interswitch {

    private $clientId;
    private $clientSecret;
    private $environment;
    private $accessToken;
    private $signature;
    private $signatureMethod;
    private $nonce;
    private $timestamp;

    public function __construct($clientId, $clientSecret, $environment = null) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        if ($environment !== null) {
            $this->environment = $environment;
        }
    }

    static function send($uri, $httpMethod, $data) {
        $this->nonce = Utils::generateNonce();
        $this->timestamp = Utils::generateTimestamp();
        $this->signatureMethod = Constants::SIGNATURE_METHOD_VALUE;
        $this->signature = Utils::generateSignature($this->clientId, $this->clientSecret, $uri, $httpMethod, $this->timestamp, $this->nonce, NULL);
        if ($this->environment === NULL) {
            $passportUrl = Constants::SANDBOX_BASE_URL . Constants::PASSPORT_RESOURCE_URL;
            $uri = Constants::SANDBOX_BASE_URL . $uri;
        } else {
            $passportUrl = Constants::PRODUCTION_BASE_URL . Constants::PASSPORT_RESOURCE_URL;
            $uri = Constants::PRODUCTION_BASE_URL . $uri;
        }

        $passportResponse = Utils::generateAccessToken($this->clientId, $this->clientSecret, $passortUrl);
        if ($passportResponse[Constants::HTTP_CODE] === 200) {
            $this->accessToken = json_decode($passportResponse[Constants::RESPONSE_BODY], true)['access_token'];
        } else {
            return $passportResponse;
        }

        $response = Utils::doREST(Constants::CONTENT_TYPE, $this->accessToken, $this->signatureMethod, $this->signature, $this->timestamp, $this->nonce, $uri, $data);

        return $response;
    }

    static function getAuthData($publicCertPath, $version, $pan, $expDate, $cvv, $pin) {
        $authDataCipher = $version . 'Z' . $pan . 'Z' . $pin . 'Z' . $expDate . 'Z' . $cvv;

        $fp = fopen($publicCertPath, "r");
        $pub_key = fread($fp, 8192);
        fclose($fp);

        openssl_public_encrypt($authDataCipher, $encryptedData, $pub_key);

        return(base64_encode($encryptedData));
    }

    function getAccessToken() {
        return $this->accessToken;
    }

    function getSignature() {
        return $this->signature;
    }

    function getSignatureMethod() {
        return $this->signatureMethod;
    }

    function getNonce() {
        return $this->nonce;
    }

    function getTimestamp() {
        return $this->timestamp;
    }

}
