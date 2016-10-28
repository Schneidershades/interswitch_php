<?php

/**
 * Description of Ewallet
 *
 * @author Abiola.Adebanjo
 */
require_once '../lib/Interswitch.php';

use Interswitch\Interswitch as InterswitchAPI;

const CLIENT_ID = "IKIA9614B82064D632E9B6418DF358A6A4AEA84D7218";
const CLIENT_SECRET = "XCTiBtLy1G9chAnyg0z3BcaFK4cVpwDg/GTw2EmjTZ8=";
const EWALLET_RESOURCE_URL = "api/v1/ewallet/instruments";
const HTTP_CODE = "HTTP_CODE";
const RESPONSE_BODY = "RESPONSE_BODY";
const CERTIFICATE_FILE_PATH = "../paymentgateway.crt";

function getUserAccessToken() {
    if (isset($_GET['code'])) {
        // try to get an access token
        $code = $_GET['code'];
        $url = 'https://sandbox.interswitchng.com/passport/oauth/token';
        $params = array(
            "code" => $code,
            "client_id" => CLIENT_ID,
            "client_secret" => CLIENT_SECRET,
            "redirect_uri" => 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"],
            "grant_type" => "authorization_code"
        );

        $ch = curl_init();
        curl_setopt($ch, constant("CURLOPT_" . 'URL'), $url);
        curl_setopt($ch, constant("CURLOPT_" . 'POST'), true);
        curl_setopt($ch, constant("CURLOPT_" . 'POSTFIELDS'), $params);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if ($info['http_code'] === 200) {
            header('Content-Type: ' . $info['content_type']);
            return json_decode($output, true)['access_token'];
        } else {
            return 'An error happened';
        }
    } else {

        $url = "https://sandbox.interswitchng.com/passport/oauth/authorize";

        $params = array(
            "response_type" => "code",
            "client_id" => CLIENT_ID,
            "redirect_uri" => 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"],
            "scope" => "profile"
        );

        $request_to = $url . '?' . http_build_query($params);

        header("Location: " . $request_to);
    }
}

function getPaymentInstruments($userAccessToken, $interswitchAPI) {
    $httpMethod = "GET";

    return $interswitchAPI->sendWithAccessToken(EWALLET_RESOURCE_URL, $httpMethod, null, $userAccessToken);
}

function test() {
    $interswitchAPI = new InterswitchAPI(CLIENT_ID, CLIENT_SECRET);
//    $userAccessToken = "eyJhbGciOiJSUzI1NiJ9.eyJsYXN0TmFtZSI6IkpBTSIsIm1lcmNoYW50X2NvZGUiOiJNWDE4NyIsInByb2R1Y3Rpb25fcGF5bWVudF9jb2RlIjoiMDQyNTk0MTMwMjQ2IiwidXNlcl9uYW1lIjoiYXBpLWphbUBpbnRlcnN3aXRjaGdyb3VwLmNvbSIsInJlcXVlc3Rvcl9pZCI6IjAwMTE3NjE0OTkyIiwibW9iaWxlTm8iOiIyMzQ4MDk4Njc0NTIzIiwicGF5YWJsZV9pZCI6IjIzMjQiLCJjbGllbnRfaWQiOiJJS0lBOTYxNEI4MjA2NEQ2MzJFOUI2NDE4REYzNThBNkE0QUVBODRENzIxOCIsImZpcnN0TmFtZSI6IkFQSSIsImVtYWlsVmVyaWZpZWQiOnRydWUsImF1ZCI6WyJpc3ctY29sbGVjdGlvbnMiLCJpc3ctcGF5bWVudGdhdGV3YXkiLCJwYXNzcG9ydCIsInZhdWx0Il0sInNjb3BlIjpbInByb2ZpbGUiXSwiZXhwIjoxNDc3NzEwNTU5LCJtb2JpbGVOb1ZlcmlmaWVkIjp0cnVlLCJqdGkiOiI3NjAxNTFmYS1mZGY3LTQ0ZDYtYmQzZi1iYTVmODNjZTMwYTciLCJlbWFpbCI6ImFwaS1qYW1AaW50ZXJzd2l0Y2hncm91cC5jb20iLCJwYXNzcG9ydElkIjoiNjExZGY3NmEtYjQzMi00NzM3LTljNjQtNzYwN2RhZGNhY2FkIiwicGF5bWVudF9jb2RlIjoiMDUxNDE5ODE1NDY4NSJ9.VeaYprAt6NB993PZsiRZygcj-xXoan-Dabxz8KNh5JpNRRAYCKqbtiSbybKSlHFSMczajhHYgUQWYs1TfOZ0YknLISoYQ-z2O4FMhvAqNUrP00dzTnpb-tMqwLJfMF-1CyyFESH3xgx2UG-Livjlpm3sQrOzpcQushl11oByNdZIrWr9JVq465oPy4b22NrdpiygkmA3g88ynmJAseYSK0fS4KfXEg85Ik7sEzu9db1AZ-idWlBnDK4v0YML8_RsQlGLB_8nb_rnMNIplGlc3lOjrgwcqDtA6C1Bz9zFqES1KzxNnk-Gr8vRuIAIxfVb_bvhmMv8xH_vvupTwqhNqQ";

    $userAccessToken = getUserAccessToken();
    $ewalletInstruments = getPaymentInstruments($userAccessToken, $interswitchAPI);
    echo "=============================================Payment Instruments=================================================================";
    echo "<br>";
    print_r($ewalletInstruments);
    echo "<br>";
}

test();
