<?php

/**
 * Description of Constants
 *
 * @author Abiola.Adebanjo
 */

namespace Interswitch;

class Constants {

    const SANDBOX_BASE_URL = "http://172.35.2.30:19081/";
//    const SANDBOX_BASE_URL = "http://172.26.40.131:19081/";
    
    const PRODUCTION_BASE_URL = "https://saturn.interswitchng.com/";
//    const PASSPORT_RESOURCE_URL = "http://172.26.40.117:6060/passport/oauth/token";
    const PASSPORT_RESOURCE_URL = "http://172.35.2.6:7073/passport/oauth/token";
    const HTTP_CODE = "HTTP_CODE";
    const RESPONSE_BODY = "RESPONSE_BODY";
    const TIMESTAMP = "TIMESTAMP";
    const NONCE = "NONCE";
    const SIGNATURE_METHOD = "SIGNATURE_METHOD";
    const SIGNATURE = "SIGNATURE";
    const AUTHORIZATION = "AUTHORIZATION";
    const CONTENT_TYPE = "application/json";
    const SIGNATURE_METHOD_VALUE = "SHA1";
    const LAGOS_TIME_ZONE = "Africa/Lagos";
    const BEARER_AUTHORIZATION_REALM = "Bearer";

}
