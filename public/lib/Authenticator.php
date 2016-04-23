<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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
                    $http_client = new GuzzleHttp\Client(["base_uri" => "https://github.com"]);
                    $oauth_request_token = $this->oauth_request_token($_REQUEST["code"]);
                    try {
                        $response = $http_client->request("POST", "/login/oauth/access_token", ['body' => $oauth_request_token]);
                    } catch (RequestException $e) {
                        error_log($e->getResponse()->getBody());
                        header("location: /login.php?error=OAuth%20Error");
                        die();
                    }
                    parse_str($response->getBody(), $token);
                    $_SESSION["oauth_token"] = $token["access_token"];
                    try {
                        $response = $http_client->request("GET", "https://api.github.com/user?access_token=".$token["access_token"]);
                    } catch (RequestException $e) {
                        error_log($e->getResponse()->getBody());
                        header("location: /login.php?error=OAuth%20Error");
                        die();
                    }
                    $profile_data = json_decode($response->getBody(), true);
                    $orgs = getenv("CHANDIKA_OAUTH_ORGS");
                    if ($orgs !== false && !in_array($profile_data["company"], explode(",", $orgs))) {
                        header("location: /login.php?error=Must%20be%20a%20member%20of%20an%20approved%organization.");
                        die();
                    }
                    if (isset($profile_data["email"])) {
                        $_SESSION["user_email"] = $profile_data["email"];
                        header("location: /index.php");
                    } else {
                        header("location: /login.php");
                    }
                    die();
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
        return http_build_query($token);
    }
}