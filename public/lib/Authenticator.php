<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Authenticator
{
    const administrator = 1;
    private $user_email;
    private $administrators = [];
    private $http_client;

    public function __construct()
    {
        $user_administrators = new UserAdministrator();
        @array_walk($user_administrators->users(), function ($value, $key) {
            $this->administrators[] = $value->email;
        });
        $oauth = getenv("CHANDIKA_OAUTH");
        if ($oauth != "OFF") {
            session_start();
            if (!isset($_SESSION["user_email"])) {
                if (isset($_REQUEST["code"])) {
                    $this->http_client = new Client(["base_uri" => "https://github.com"]);
                    $oauth_request_token = $this->oauth_request_token($_REQUEST["code"]);

                    parse_str($this->http_request("POST", "/login/oauth/access_token", ['body' => $oauth_request_token]), $token);
                    $_SESSION["oauth_token"] = $token["access_token"];

                    $profile_data = json_decode($this->http_request("GET", "https://api.github.com/user?access_token=" . $token["access_token"], []), true);

                    $org_data = json_decode($this->http_request("GET", "https://api.github.com/user/orgs?access_token=" . $token["access_token"], []), true);

                    $my_orgs = [];
                    foreach ($org_data as $org) {
                        $my_orgs[] = $org["login"];
                    }

                    $orgs = getenv("CHANDIKA_OAUTH_ORGS");
                    if ($orgs !== false && count(array_intersect($my_orgs, explode(",", $orgs))) == 0) {
                        $error = urlencode("Must be a member of an approved organization.");
                        header("location: /login.php?error=" . $error);
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

    private function oauth_request_token($code)
    {
        $token = [];
        $token["client_id"] = getenv("CHANDIKA_OAUTH_CLIENT_ID");
        $token["client_secret"] = getenv("CHANDIKA_OAUTH_CLIENT_SECRET");
        $token["code"] = $code;
        return http_build_query($token);
    }

    public function assertRole($role)
    {
        $oauth = getenv("CHANDIKA_OAUTH");
        if ($role != Authenticator::administrator || $oauth == "OFF") {
            return true;
        }
        if (!in_array($_SESSION["user_email"], $this->administrators)) {
            header("location: /index.php");
            die();
        }
        return true;
    }

    public function belongsTo($role)
    {
        $oauth = getenv("CHANDIKA_OAUTH");
        if ($oauth == "OFF") return true;
        return ($role == Authenticator::administrator) && in_array($_SESSION["user_email"], $this->administrators);
    }

    public function http_request($method, $uri, $data)
    {
        try {
            $response = $this->http_client->request($method, $uri, $data);
        } catch (RequestException $e) {
            error_log($e->getResponse()->getBody());
            header("location: /login.php?error=OAuth%20Error");
            die();
        }
        return $response->getBody();
    }
}