<?php
use GuzzleHttp\Client;

class Authenticator
{
    private $user_email;

    public function __construct()
    {
        $oauth = getenv("CHANDIKA_OAUTH");
        if ($oauth != "OFF") {
            session_start();
            if (!isset($_SESSION["user_email"])) {
                if (isset($_REQUEST["code"])) {
                    $http_client = new GuzzleHttp\Client();
                    $token = json_decode($http_client->request("POST", getenv("CHANDIKA_OAUTH_URL")."oauth/token", ['body' => $this->oauth_request_token($_REQUEST["code"])]), true);
                    $_SESSION["oauth_token"] = $token["access_token"];
                    $profile = json_decode($http_client->request("GET", getenv("CHANDIKA_OAUTH_URL")."api/v1/profile"), true);
                    $_SESSION["user_email"] = $profile["email"];
                }
                header("location: /login.php");
                die();
            }
            $this->user_email = $_SESSION["user_email"];
        }
    }

    public function user_email()
    {
        return $this->user_email;
    }

    private function oauth_request_token($code) {
        $token = [];
        $token["client_id"] = getenv("CHANDIKA_OAUTH_CLIENT_ID");
        $token["client_secret"] = getenv("CHANDIKA_OAUTH_CLIENT_SECRET");
        $token["code"] = $code;
        $token["grant_type"] = "authorization_code";
        $token["redirect_uri"] = "urn:ietf:wg:oauth:2.0:oob";
        return json_encode($token);
    }
}