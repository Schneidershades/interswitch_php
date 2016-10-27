<?php

/**
 * Description of InterswitchAuth
 *
 * @author Abiola.Adebanjo
 */

namespace Interswitch;

include_once 'Util\Utils.php';
include_once 'Util\Constants.php';
include_once 'Util\HttpClient.php';


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

    function send($uri, $httpMethod, $data, $headers = null, $signedParameters = null) {
        $this->nonce = Utils::generateNonce();
        $this->timestamp = Utils::generateTimestamp();
        $this->signatureMethod = Constants::SIGNATURE_METHOD_VALUE;

        if ($this->environment === NULL) {
//            $passportUrl = Constants::SANDBOX_BASE_URL . Constants::PASSPORT_RESOURCE_URL;
            $passportUrl = Constants::PASSPORT_RESOURCE_URL;

            $uri = Constants::SANDBOX_BASE_URL . $uri;
        } else {
            $passportUrl = Constants::PRODUCTION_BASE_URL . Constants::PASSPORT_RESOURCE_URL;
            $uri = Constants::PRODUCTION_BASE_URL . $uri;
        }
        $this->signature = Utils::generateSignature($this->clientId, $this->clientSecret, $uri, $httpMethod, $this->timestamp, $this->nonce, $signedParameters);
        
        $passportResponse = Utils::generateAccessToken($this->clientId, $this->clientSecret, $passportUrl);
        if ($passportResponse[Constants::HTTP_CODE] === 200) {
            $this->accessToken = json_decode($passportResponse[Constants::RESPONSE_BODY], true)['access_token'];
        } else {
            return $passportResponse;
        }

        $authorization = 'Bearer ' . $this->accessToken;

        $constantHeaders = [
            'Content-Type: ' . Constants::CONTENT_TYPE,
            'Authorization: ' . $authorization,
            'SignatureMethod: ' . $this->signatureMethod,
            'Signature: ' . $this->signature,
            'Timestamp: ' . $this->timestamp,
            'Nonce: ' . $this->nonce
        ];


        if ($headers !== null && is_array($headers)) {
            $requestHeaders = array_merge($headers, $constantHeaders);
            $response = HttpClient::send($requestHeaders, $httpMethod, $uri, $data);
        } else {
            $response = HttpClient::send($constantHeaders, $httpMethod, $uri, $data);
        }

        return $response;
    }

    function sendWithAccessToken($uri, $httpMethod, $data, $accessToken, $headers = null, $signedParameters = null) {
        $this->nonce = Utils::generateNonce();
        $this->timestamp = Utils::generateTimestamp();
        $this->signatureMethod = Constants::SIGNATURE_METHOD_VALUE;

        if ($this->environment === NULL) {
//            $passportUrl = Constants::SANDBOX_BASE_URL . Constants::PASSPORT_RESOURCE_URL;
            $passportUrl = Constants::PASSPORT_RESOURCE_URL;

            $uri = Constants::SANDBOX_BASE_URL . $uri;
        } else {
            $passportUrl = Constants::PRODUCTION_BASE_URL . Constants::PASSPORT_RESOURCE_URL;
            $uri = Constants::PRODUCTION_BASE_URL . $uri;
        }
        $this->signature = Utils::generateSignature($this->clientId, $this->clientSecret, $uri, $httpMethod, $this->timestamp, $this->nonce, $signedParameters);

        $authorization = 'Bearer ' . $accessToken;

        $constantHeaders = [
            'Content-Type: ' . Constants::CONTENT_TYPE,
            'Authorization: ' . $authorization,
            'SignatureMethod: ' . $this->signatureMethod,
            'Signature: ' . $this->signature,
            'Timestamp: ' . $this->timestamp,
            'Nonce: ' . $this->nonce
        ];


        if ($headers !== null && is_array($headers)) {
            $requestHeaders = array_merge($headers, $constantHeaders);
            $response = HttpClient::send($requestHeaders, $httpMethod, $uri, $data);
        } else {
            $response = HttpClient::send($constantHeaders, $httpMethod, $uri, $data);
        }

        return $response;
    }

    function getAuthData($publicCertPath, $version, $pan, $expDate, $cvv, $pin) {
        $authDataCipher = $version . 'Z' . $pan . 'Z' . $pin . 'Z' . $expDate . 'Z' . $cvv;

        $fp = fopen($publicCertPath, "r");
        $pub_key = fread($fp, 8192);
        fclose($fp);

        openssl_public_encrypt($authDataCipher, $encryptedData, $pub_key);

        $authData = base64_encode($encryptedData);

        return $authData;
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
